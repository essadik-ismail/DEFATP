<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;
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

        if ($this->canUseZipArchive()) {
            $this->populateTemplateWithZipArchive($templatePath, $replacements, $outputPath);

            return $outputPath;
        }

        $this->populateTemplateWithTarFallback($templatePath, $replacements, $outputPath);

        return $outputPath;
    }

    /**
     * @return array<int, string>
     */
    public function extractPlaceholders(string $templatePath): array
    {
        if (!is_file($templatePath)) {
            throw new RuntimeException('Le modele DOCX est introuvable.');
        }

        if ($this->canUseZipArchive()) {
            return $this->extractPlaceholdersWithZipArchive($templatePath);
        }

        return $this->extractPlaceholdersWithTarFallback($templatePath);
    }

    public function canConvertDocxToPdf(): bool
    {
        return $this->findMicrosoftWordExecutable() !== null;
    }

    public function convertDocxToPdf(string $docxPath, ?string $outputFilename = null): string
    {
        if (!is_file($docxPath)) {
            throw new RuntimeException('Le fichier DOCX source est introuvable.');
        }

        $wordExecutable = $this->findMicrosoftWordExecutable();
        if ($wordExecutable === null) {
            throw new RuntimeException('Microsoft Word est requis pour convertir la lettre en PDF sur ce serveur.');
        }

        $outputDirectory = storage_path('app/generated');

        if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0777, true) && !is_dir($outputDirectory)) {
            throw new RuntimeException('Impossible de creer le dossier de generation des documents.');
        }

        $outputFilename = $outputFilename ?: Str::uuid() . '.pdf';
        $outputPath = $outputDirectory . DIRECTORY_SEPARATOR . $outputFilename;

        if (is_file($outputPath) && !unlink($outputPath)) {
            throw new RuntimeException('Impossible de preparer le fichier PDF genere.');
        }

        $this->runWordPdfConversion($docxPath, $outputPath);

        if (!is_file($outputPath)) {
            throw new RuntimeException('Le fichier PDF n\'a pas ete genere.');
        }

        return $outputPath;
    }

    private function populateTemplateWithZipArchive(string $templatePath, array $replacements, string $outputPath): void
    {
        if (!copy($templatePath, $outputPath)) {
            throw new RuntimeException('Impossible de copier le modele DOCX.');
        }

        $archive = new ZipArchive();

        if ($archive->open($outputPath) !== true) {
            throw new RuntimeException('Impossible d\'ouvrir le fichier DOCX genere.');
        }

        foreach ($this->getArchiveEntryNames($archive) as $entryName) {
            $this->updateArchiveEntry($archive, $entryName, $replacements);
        }

        $archive->close();
    }

    /**
     * @return array<int, string>
     */
    private function extractPlaceholdersWithZipArchive(string $templatePath): array
    {
        $archive = new ZipArchive();

        if ($archive->open($templatePath) !== true) {
            throw new RuntimeException('Impossible d\'ouvrir le modele DOCX.');
        }

        $placeholders = [];

        foreach ($this->getArchiveEntryNames($archive) as $entryName) {
            if (!$this->shouldProcessEntry($entryName)) {
                continue;
            }

            $xml = $archive->getFromName($entryName);
            if ($xml === false) {
                continue;
            }

            $placeholders = $this->mergePlaceholders($placeholders, $this->extractPlaceholdersFromXml($xml));
        }

        $archive->close();

        return $placeholders;
    }

    private function populateTemplateWithTarFallback(string $templatePath, array $replacements, string $outputPath): void
    {
        $workingDirectory = $this->extractArchiveToTemporaryDirectory($templatePath);

        try {
            foreach ($this->getExtractedXmlPaths($workingDirectory) as $filePath) {
                $xml = file_get_contents($filePath);
                if ($xml === false || !str_contains($xml, '{{')) {
                    continue;
                }

                $updatedXml = $this->replacePlaceholdersInXml($xml, $replacements);

                if ($updatedXml === $xml) {
                    continue;
                }

                file_put_contents($filePath, $updatedXml);
            }

            $this->createArchiveFromDirectory($workingDirectory, $outputPath);
        } finally {
            $this->deleteDirectory($workingDirectory);
        }
    }

    /**
     * @return array<int, string>
     */
    private function extractPlaceholdersWithTarFallback(string $templatePath): array
    {
        $workingDirectory = $this->extractArchiveToTemporaryDirectory($templatePath);

        try {
            $placeholders = [];

            foreach ($this->getExtractedXmlPaths($workingDirectory) as $filePath) {
                $xml = file_get_contents($filePath);
                if ($xml === false) {
                    continue;
                }

                $placeholders = $this->mergePlaceholders($placeholders, $this->extractPlaceholdersFromXml($xml));
            }

            return $placeholders;
        } finally {
            $this->deleteDirectory($workingDirectory);
        }
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

    /**
     * @return array<int, string>
     */
    private function extractPlaceholdersFromXml(string $xml): array
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->preserveWhiteSpace = true;
        $document->formatOutput = false;

        $previousUseInternalErrors = libxml_use_internal_errors(true);
        $loaded = $document->loadXML($xml);
        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        if (!$loaded) {
            return [];
        }

        $fullText = '';

        foreach ($this->buildTextSegments($document) as $segment) {
            $fullText .= $segment['text'];
        }

        if (!preg_match_all('/\{\{\s*([^{}]+?)\s*\}\}/u', $fullText, $matches)) {
            return [];
        }

        $placeholders = [];

        foreach ($matches[1] as $match) {
            $placeholder = trim((string) $match);

            if ($placeholder === '' || in_array($placeholder, $placeholders, true)) {
                continue;
            }

            $placeholders[] = $placeholder;
        }

        return $placeholders;
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

    private function canUseZipArchive(): bool
    {
        return class_exists(ZipArchive::class);
    }

    private function findMicrosoftWordExecutable(): ?string
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            return null;
        }

        $baseDirectories = array_filter([
            getenv('ProgramFiles') ?: null,
            getenv('ProgramFiles(x86)') ?: null,
        ]);

        foreach ($baseDirectories as $baseDirectory) {
            $patterns = [
                $baseDirectory . '\\Microsoft Office\\root\\Office*\\WINWORD.EXE',
                $baseDirectory . '\\Microsoft Office\\Office*\\WINWORD.EXE',
            ];

            foreach ($patterns as $pattern) {
                $matches = glob($pattern);

                if ($matches === false || $matches === []) {
                    continue;
                }

                usort($matches, static fn (string $left, string $right): int => strcmp($right, $left));

                foreach ($matches as $match) {
                    if (is_file($match)) {
                        return $match;
                    }
                }
            }
        }

        return null;
    }

    private function runWordPdfConversion(string $docxPath, string $outputPath): void
    {
        $script = <<<'VBS'
Option Explicit

Dim sourcePath, targetPath, wordApp, document
sourcePath = WScript.Arguments.Item(0)
targetPath = WScript.Arguments.Item(1)

Set wordApp = Nothing
Set document = Nothing

On Error Resume Next

Set wordApp = CreateObject("Word.Application")
If Err.Number <> 0 Or wordApp Is Nothing Then
    Fail "Impossible de demarrer Microsoft Word: " & Err.Description
End If

wordApp.Visible = False
wordApp.DisplayAlerts = 0

Err.Clear
Set document = wordApp.Documents.OpenNoRepairDialog(sourcePath, False, True)
If Err.Number <> 0 Or document Is Nothing Then
    Err.Clear
    Set document = wordApp.Documents.Open(sourcePath, False, True)
End If

If Err.Number <> 0 Or document Is Nothing Then
    Cleanup wordApp, document
    Fail "Impossible d'ouvrir le fichier DOCX dans Word: " & Err.Description
End If

Err.Clear
document.ExportAsFixedFormat targetPath, 17
If Err.Number <> 0 Then
    Cleanup wordApp, document
    Fail "Impossible d'exporter le PDF depuis Word: " & Err.Description
End If

Cleanup wordApp, document

Sub Cleanup(ByRef app, ByRef doc)
    On Error Resume Next
    If Not doc Is Nothing Then
        doc.Close False
        Set doc = Nothing
    End If

    If Not app Is Nothing Then
        app.Quit
        Set app = Nothing
    End If
End Sub

Sub Fail(message)
    WScript.StdErr.WriteLine message
    WScript.Quit 1
End Sub
VBS;

        $this->runVbscript(
            $script,
            [$docxPath, $outputPath],
            'Impossible de convertir le DOCX en PDF.'
        );
    }

    /**
     * @return array<int, string>
     */
    private function getArchiveEntryNames(ZipArchive $archive): array
    {
        $entryNames = [];

        for ($i = 0; $i < $archive->numFiles; $i++) {
            $entryNames[] = $archive->getNameIndex($i);
        }

        return $entryNames;
    }

    private function updateArchiveEntry(ZipArchive $archive, string $entryName, array $replacements): void
    {
        if (!$this->shouldProcessEntry($entryName)) {
            return;
        }

        $xml = $archive->getFromName($entryName);
        if ($xml === false || !str_contains($xml, '{{')) {
            return;
        }

        $updatedXml = $this->replacePlaceholdersInXml($xml, $replacements);

        if ($updatedXml === $xml) {
            return;
        }

        $archive->deleteName($entryName);
        $archive->addFromString($entryName, $updatedXml);
    }

    private function extractArchiveToTemporaryDirectory(string $templatePath): string
    {
        $workingDirectory = storage_path('app/temp/docx/' . Str::uuid());

        if (!is_dir($workingDirectory) && !mkdir($workingDirectory, 0777, true) && !is_dir($workingDirectory)) {
            throw new RuntimeException('Impossible de creer un dossier temporaire pour le DOCX.');
        }

        try {
            $this->runArchiveProcess(
                [$this->tarBinary(), '-xf', $templatePath, '-C', $workingDirectory],
                'Impossible d\'extraire le modele DOCX.'
            );
        } catch (\Throwable $e) {
            $this->deleteDirectory($workingDirectory);

            throw $e;
        }

        return $workingDirectory;
    }

    private function createArchiveFromDirectory(string $directory, string $outputPath): void
    {
        if (is_file($outputPath) && !unlink($outputPath)) {
            throw new RuntimeException('Impossible de preparer le fichier DOCX genere.');
        }

        $entries = array_values(array_filter(
            scandir($directory) ?: [],
            static fn (string $entry): bool => $entry !== '.' && $entry !== '..'
        ));

        if ($entries === []) {
            throw new RuntimeException('Impossible de reconstruire le DOCX genere.');
        }

        if (PHP_OS_FAMILY === 'Windows') {
            $this->createArchiveWithVbscriptZip($directory, $outputPath);

            return;
        }

        $this->runArchiveProcess(
            array_merge([$this->tarBinary(), '-a', '-cf', $outputPath, '-C', $directory], $entries),
            'Impossible de reconstruire le fichier DOCX genere.'
        );
    }

    /**
     * @return array<int, string>
     */
    private function getExtractedXmlPaths(string $workingDirectory): array
    {
        $wordDirectory = $workingDirectory . DIRECTORY_SEPARATOR . 'word';

        if (!is_dir($wordDirectory)) {
            return [];
        }

        $paths = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($wordDirectory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $item) {
            if (!$item->isFile() || !str_ends_with($item->getFilename(), '.xml')) {
                continue;
            }

            $relativePath = str_replace('\\', '/', substr($item->getPathname(), strlen($workingDirectory) + 1));

            if (!$this->shouldProcessEntry($relativePath)) {
                continue;
            }

            $paths[] = $item->getPathname();
        }

        sort($paths, SORT_STRING);

        return $paths;
    }

    /**
     * @param array<int, string> $existing
     * @param array<int, string> $newPlaceholders
     * @return array<int, string>
     */
    private function mergePlaceholders(array $existing, array $newPlaceholders): array
    {
        foreach ($newPlaceholders as $placeholder) {
            if (!in_array($placeholder, $existing, true)) {
                $existing[] = $placeholder;
            }
        }

        return $existing;
    }

    /**
     * @param array<int, string> $command
     */
    private function runArchiveProcess(array $command, string $errorMessage, array $environment = []): void
    {
        try {
            $process = new Process($command);
            if ($environment !== []) {
                $process->setEnv($environment + $process->getEnv());
            }
            $process->run();
        } catch (\Throwable $e) {
            throw new RuntimeException($errorMessage . ' ' . $e->getMessage(), 0, $e);
        }

        if ($process->isSuccessful()) {
            return;
        }

        $details = trim($process->getErrorOutput() ?: $process->getOutput());

        throw new RuntimeException($errorMessage . ($details !== '' ? ' ' . $details : ''));
    }

    private function createArchiveWithVbscriptZip(string $sourceDirectory, string $archivePath): void
    {
        $expectedFiles = $this->countFiles($sourceDirectory);

        $script = <<<'VBS'
Option Explicit

Dim sourceDir, archivePath, expectedFiles
sourceDir = WScript.Arguments.Item(0)
archivePath = WScript.Arguments.Item(1)
expectedFiles = CLng(WScript.Arguments.Item(2))

Dim shellApp, sourceNamespace, zipNamespace, startTime
Set shellApp = CreateObject("Shell.Application")

CreateEmptyZip archivePath

Set sourceNamespace = shellApp.NameSpace(sourceDir)
Set zipNamespace = shellApp.NameSpace(archivePath)

If sourceNamespace Is Nothing Then
    Fail "Impossible de lire le dossier source pour le ZIP."
End If

If zipNamespace Is Nothing Then
    Fail "Impossible d'ouvrir le fichier ZIP cible."
End If

zipNamespace.CopyHere sourceNamespace.Items, &H14

startTime = Timer
Do
    WScript.Sleep 200
    If CountFilesInNamespace(zipNamespace) >= expectedFiles Then Exit Do
    If ElapsedSeconds(startTime) > 30 Then Exit Do
Loop

If CountFilesInNamespace(zipNamespace) < expectedFiles Then
    Fail "Le ZIP genere est incomplet."
End If

Sub CreateEmptyZip(path)
    Dim stream
    Set stream = CreateObject("ADODB.Stream")
    stream.Type = 2
    stream.Charset = "iso-8859-1"
    stream.Open
    stream.WriteText "PK" & Chr(5) & Chr(6) & String(18, Chr(0))
    stream.SaveToFile path, 2
    stream.Close
End Sub

Function CountFilesInNamespace(folder)
    Dim item, total
    total = 0

    If folder Is Nothing Then
        CountFilesInNamespace = 0
        Exit Function
    End If

    For Each item In folder.Items
        If item.IsFolder Then
            total = total + CountFilesInNamespace(item.GetFolder)
        Else
            total = total + 1
        End If
    Next

    CountFilesInNamespace = total
End Function

Function ElapsedSeconds(startValue)
    Dim currentValue
    currentValue = Timer
    If currentValue < startValue Then
        currentValue = currentValue + 86400
    End If

    ElapsedSeconds = currentValue - startValue
End Function

Sub Fail(message)
    WScript.StdErr.WriteLine message
    WScript.Quit 1
End Sub
VBS;

        $this->runVbscript(
            $script,
            [$sourceDirectory, $archivePath, (string) $expectedFiles],
            'Impossible de reconstruire le fichier DOCX genere.'
        );
    }

    /**
     * @param array<int, string> $arguments
     */
    private function runVbscript(string $script, array $arguments, string $errorMessage): void
    {
        $temporaryDirectory = storage_path('app/temp/scripts');

        if (!is_dir($temporaryDirectory) && !mkdir($temporaryDirectory, 0777, true) && !is_dir($temporaryDirectory)) {
            throw new RuntimeException($errorMessage . ' Impossible de creer le dossier temporaire de script.');
        }

        $scriptPath = $temporaryDirectory . DIRECTORY_SEPARATOR . Str::uuid() . '.vbs';

        if (file_put_contents($scriptPath, $script) === false) {
            throw new RuntimeException($errorMessage . ' Impossible d\'ecrire le script temporaire.');
        }

        try {
            $this->runArchiveProcess(
                array_merge(['cscript.exe', '//nologo', $scriptPath], $arguments),
                $errorMessage
            );
        } finally {
            @unlink($scriptPath);
        }
    }

    private function countFiles(string $directory): int
    {
        if (!is_dir($directory)) {
            return 0;
        }

        $count = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $item) {
            if ($item->isFile()) {
                $count++;
            }
        }

        return $count;
    }

    private function tarBinary(): string
    {
        return 'tar';
    }

    private function deleteDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                @rmdir($item->getPathname());
                continue;
            }

            @unlink($item->getPathname());
        }

        @rmdir($path);
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
