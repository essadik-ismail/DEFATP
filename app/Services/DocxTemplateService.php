<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class DocxTemplateService
{
    /**
     * Fill a DOCX template and return the generated file path.
     */
    public function populateTemplate(string $templatePath, array $replacements, ?string $outputFilename = null): string
    {
        if (!is_file($templatePath)) {
            throw new RuntimeException('Le modele DOCX est introuvable.');
        }

        $outputDirectory = storage_path('app/generated');

        if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0777, true) && !is_dir($outputDirectory)) {
            throw new RuntimeException('Impossible de creer le dossier de generation des documents.');
        }

        $outputFilename = $outputFilename ?: Str::uuid() . '.docx';
        $outputPath = $outputDirectory . DIRECTORY_SEPARATOR . $outputFilename;

        if (!copy($templatePath, $outputPath)) {
            throw new RuntimeException('Impossible de copier le modele DOCX.');
        }

        $archive = new ZipArchive();

        if ($archive->open($outputPath) !== true) {
            throw new RuntimeException('Impossible d\'ouvrir le fichier DOCX genere.');
        }

        $entryNames = [];
        for ($i = 0; $i < $archive->numFiles; $i++) {
            $entryNames[] = $archive->getNameIndex($i);
        }

        foreach ($entryNames as $entryName) {
            if (!$this->shouldProcessEntry($entryName)) {
                continue;
            }

            $xml = $archive->getFromName($entryName);
            if ($xml === false || !str_contains($xml, '{{')) {
                continue;
            }

            $updatedXml = $this->replacePlaceholdersInXml($xml, $replacements);

            if ($updatedXml === $xml) {
                continue;
            }

            $archive->deleteName($entryName);
            $archive->addFromString($entryName, $updatedXml);
        }

        $archive->close();

        return $outputPath;
    }

    private function shouldProcessEntry(string $entryName): bool
    {
        return str_starts_with($entryName, 'word/') && str_ends_with($entryName, '.xml');
    }

    private function replacePlaceholdersInXml(string $xml, array $replacements): string
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->preserveWhiteSpace = true;
        $document->formatOutput = false;

        $previousUseInternalErrors = libxml_use_internal_errors(true);
        $loaded = $document->loadXML($xml);
        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        if (!$loaded) {
            return $xml;
        }

        foreach ($replacements as $key => $value) {
            $placeholder = $this->normalizePlaceholder((string) $key);

            if ($placeholder === '') {
                continue;
            }

            while ($this->replaceFirstOccurrence($document, $placeholder, (string) $value)) {
                // Re-run until every occurrence is replaced, including placeholders split across runs.
            }
        }

        return $document->saveXML() ?: $xml;
    }

    private function normalizePlaceholder(string $key): string
    {
        $trimmed = trim($key);

        if ($trimmed === '') {
            return '';
        }

        if (str_starts_with($trimmed, '{{') && str_ends_with($trimmed, '}}')) {
            return $trimmed;
        }

        return '{{' . $trimmed . '}}';
    }

    private function replaceFirstOccurrence(DOMDocument $document, string $placeholder, string $replacement): bool
    {
        $segments = $this->buildTextSegments($document);
        $fullText = '';

        foreach ($segments as $segment) {
            $fullText .= $segment['text'];
        }

        $position = mb_strpos($fullText, $placeholder, 0, 'UTF-8');
        if ($position === false) {
            return false;
        }

        $placeholderLength = mb_strlen($placeholder, 'UTF-8');
        $endPosition = $position + $placeholderLength;

        $startIndex = null;
        $endIndex = null;

        foreach ($segments as $index => $segment) {
            $segmentStart = $segment['start'];
            $segmentEnd = $segmentStart + $segment['length'];

            if ($startIndex === null && $position >= $segmentStart && $position < $segmentEnd) {
                $startIndex = $index;
            }

            if ($endPosition > $segmentStart && $endPosition <= $segmentEnd) {
                $endIndex = $index;
                break;
            }
        }

        if ($startIndex === null || $endIndex === null) {
            return false;
        }

        $startSegment = $segments[$startIndex];
        $endSegment = $segments[$endIndex];

        $startNode = $startSegment['node'];
        $endNode = $endSegment['node'];

        $startOffset = $position - $startSegment['start'];
        $endOffset = $endPosition - $endSegment['start'];

        $startText = $startSegment['text'];
        $endText = $endSegment['text'];

        $prefix = mb_substr($startText, 0, $startOffset, 'UTF-8');
        $suffix = mb_substr($endText, $endOffset, null, 'UTF-8');

        if ($startIndex === $endIndex) {
            $startNode->nodeValue = $prefix . $replacement . $suffix;
            return true;
        }

        $startNode->nodeValue = $prefix . $replacement;

        for ($i = $startIndex + 1; $i < $endIndex; $i++) {
            $segments[$i]['node']->nodeValue = '';
        }

        $endNode->nodeValue = $suffix;

        return true;
    }

    /**
     * @return array<int, array{node:\DOMNode,text:string,start:int,length:int}>
     */
    private function buildTextSegments(DOMDocument $document): array
    {
        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        $segments = [];
        $cursor = 0;

        foreach ($xpath->query('//w:t') as $node) {
            $text = $node->textContent ?? '';
            $length = mb_strlen($text, 'UTF-8');

            $segments[] = [
                'node' => $node,
                'text' => $text,
                'start' => $cursor,
                'length' => $length,
            ];

            $cursor += $length;
        }

        return $segments;
    }
}
