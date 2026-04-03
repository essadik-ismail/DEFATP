<?php

namespace Tests\Unit;

use App\Services\DocxTemplateService;
use Symfony\Component\Process\Process;
use Tests\TestCase;
use ZipArchive;

class DocxTemplateServiceTest extends TestCase
{
    public function test_it_replaces_placeholders_split_across_word_runs(): void
    {
        if (PHP_OS_FAMILY === 'Windows' && !class_exists(ZipArchive::class)) {
            $this->markTestSkipped('DOCX repacking fallback cannot be exercised reliably in the restricted Windows test shell.');
        }

        $paths = $this->createTemplateFixture();
        $outputName = 'docx-template-test-output.docx';

        try {
            $service = new DocxTemplateService();
            $outputPath = $service->populateTemplate($paths['template_path'], [
                'Exploitant' => 'Societe Atlas',
                'DateAdj' => '01/04/2026',
            ], $outputName);

            $resultXml = $this->readArchiveEntry($outputPath, 'word/document.xml');

            $this->assertStringContainsString('Societe Atlas', $resultXml);
            $this->assertStringContainsString('01/04/2026', $resultXml);
            $this->assertStringNotContainsString('{{Exploitant}}', $resultXml);
            $this->assertStringNotContainsString('{{DateAdj}}', $resultXml);
        } finally {
            $this->deletePath($paths['template_directory']);
            $this->deletePath($paths['template_path']);
            $this->deletePath(storage_path('app/generated/' . $outputName));
        }
    }

    public function test_it_extracts_placeholders_split_across_word_runs(): void
    {
        $paths = $this->createTemplateFixture();

        try {
            $service = new DocxTemplateService();
            $placeholders = $service->extractPlaceholders($paths['template_path']);

            $this->assertSame(['Exploitant', 'DateAdj'], $placeholders);
        } finally {
            $this->deletePath($paths['template_directory']);
            $this->deletePath($paths['template_path']);
        }
    }

    /**
     * @return array{template_directory:string,template_path:string}
     */
    private function createTemplateFixture(): array
    {
        $baseDirectory = storage_path('framework/testing/' . uniqid('docx-template-', true));
        $wordDirectory = $baseDirectory . DIRECTORY_SEPARATOR . 'word';

        if (!is_dir($wordDirectory) && !mkdir($wordDirectory, 0777, true) && !is_dir($wordDirectory)) {
            $this->fail('Unable to create test fixture directory.');
        }

        $documentXml = <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
    <w:body>
        <w:p>
            <w:r><w:t>{{Ex</w:t></w:r>
            <w:r><w:t>ploitant}}</w:t></w:r>
            <w:r><w:t xml:space="preserve"> - </w:t></w:r>
            <w:r><w:t>{{Da</w:t></w:r>
            <w:r><w:t>teAdj}}</w:t></w:r>
        </w:p>
    </w:body>
</w:document>
XML;

        file_put_contents(
            $baseDirectory . DIRECTORY_SEPARATOR . '[Content_Types].xml',
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"></Types>'
        );
        file_put_contents($wordDirectory . DIRECTORY_SEPARATOR . 'document.xml', $documentXml);

        $templatePath = storage_path('framework/testing/' . uniqid('docx-template-', true) . '.docx');
        $this->createArchive($baseDirectory, $templatePath);

        return [
            'template_directory' => $baseDirectory,
            'template_path' => $templatePath,
        ];
    }

    private function createArchive(string $sourceDirectory, string $archivePath): void
    {
        if (class_exists(ZipArchive::class)) {
            $archive = new ZipArchive();
            $status = $archive->open($archivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $this->assertTrue($status === true, 'Unable to create the DOCX archive for the test.');
            $archive->addFile($sourceDirectory . DIRECTORY_SEPARATOR . '[Content_Types].xml', '[Content_Types].xml');
            $archive->addFile($sourceDirectory . DIRECTORY_SEPARATOR . 'word' . DIRECTORY_SEPARATOR . 'document.xml', 'word/document.xml');
            $archive->close();

            return;
        }

        $this->runProcess([
            'powershell',
            '-NoProfile',
            '-NonInteractive',
            '-Command',
            "Add-Type -AssemblyName System.IO.Compression; Add-Type -AssemblyName System.IO.Compression.FileSystem; if (Test-Path -LiteralPath '$archivePath') { Remove-Item -LiteralPath '$archivePath' -Force }; \$zip = [System.IO.Compression.ZipFile]::Open('$archivePath', [System.IO.Compression.ZipArchiveMode]::Create); try { Get-ChildItem -LiteralPath '$sourceDirectory' -Recurse -File | ForEach-Object { \$entryName = \$_.FullName.Substring('$sourceDirectory'.TrimEnd('\\').Length + 1).Replace('\\','/'); [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile(\$zip, \$_.FullName, \$entryName, [System.IO.Compression.CompressionLevel]::Optimal) | Out-Null } } finally { \$zip.Dispose() }",
        ], 'Unable to create the DOCX archive for the test.');
    }

    private function readArchiveEntry(string $archivePath, string $entryName): string
    {
        if (class_exists(ZipArchive::class)) {
            $archive = new ZipArchive();
            $status = $archive->open($archivePath);

            $this->assertTrue($status === true, 'Unable to open the generated DOCX file.');

            $contents = $archive->getFromName($entryName);
            $archive->close();

            $this->assertIsString($contents);

            return $contents;
        }

        $extractDirectory = storage_path('framework/testing/' . uniqid('docx-extract-', true));

        if (!is_dir($extractDirectory) && !mkdir($extractDirectory, 0777, true) && !is_dir($extractDirectory)) {
            $this->fail('Unable to create extraction directory for the DOCX test.');
        }

        try {
            $this->runProcess([
                'powershell',
                '-NoProfile',
                '-NonInteractive',
                '-Command',
                "Add-Type -AssemblyName System.IO.Compression; Add-Type -AssemblyName System.IO.Compression.FileSystem; if (Test-Path -LiteralPath '$extractDirectory') { Remove-Item -LiteralPath '$extractDirectory' -Recurse -Force }; New-Item -ItemType Directory -Path '$extractDirectory' -Force | Out-Null; [System.IO.Compression.ZipFile]::ExtractToDirectory('$archivePath', '$extractDirectory')",
            ], 'Unable to extract the generated DOCX file.');

            $targetPath = $extractDirectory . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $entryName);
            $contents = file_get_contents($targetPath);

            $this->assertIsString($contents);

            return $contents;
        } finally {
            $this->deletePath($extractDirectory);
        }
    }

    /**
     * @param array<int, string> $command
     */
    private function runProcess(array $command, string $failureMessage): void
    {
        $process = new Process($command);
        $process->run();

        $this->assertTrue(
            $process->isSuccessful(),
            trim($failureMessage . ' ' . ($process->getErrorOutput() ?: $process->getOutput()))
        );
    }

    private function deletePath(string $path): void
    {
        if (is_file($path)) {
            @unlink($path);

            return;
        }

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
}
