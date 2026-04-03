<?php

namespace App\Services;

use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class PdfRenderingService
{
    public function canGeneratePdf(): bool
    {
        return $this->findBrowserExecutable() !== null;
    }

    public function renderHtmlToPdf(string $html, ?string $outputFilename = null): string
    {
        $browser = $this->findBrowserExecutable();

        if ($browser === null) {
            throw new RuntimeException('Microsoft Edge est requis pour generer le PDF sur ce serveur.');
        }

        $outputDirectory = storage_path('app/generated');
        if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0777, true) && !is_dir($outputDirectory)) {
            throw new RuntimeException('Impossible de creer le dossier de generation des documents.');
        }

        $outputFilename = $outputFilename ?: Str::uuid() . '.pdf';
        $outputPath = $outputDirectory . DIRECTORY_SEPARATOR . $outputFilename;

        $temporaryDirectory = storage_path('app/temp/pdf/' . Str::uuid());
        $profileDirectory = $temporaryDirectory . DIRECTORY_SEPARATOR . 'profile';
        $htmlPath = $temporaryDirectory . DIRECTORY_SEPARATOR . 'document.html';

        if (!is_dir($profileDirectory) && !mkdir($profileDirectory, 0777, true) && !is_dir($profileDirectory)) {
            throw new RuntimeException('Impossible de creer le dossier temporaire PDF.');
        }

        if (file_put_contents($htmlPath, $html) === false) {
            throw new RuntimeException('Impossible d\'ecrire le contenu HTML temporaire du PDF.');
        }

        try {
            $process = new Process([
                $browser,
                '--headless=new',
                '--disable-gpu',
                '--no-first-run',
                '--no-default-browser-check',
                '--allow-file-access-from-files',
                '--user-data-dir=' . $profileDirectory,
                '--print-to-pdf=' . $outputPath,
                '--print-to-pdf-no-header',
                $this->toFileUrl($htmlPath),
            ]);
            $process->setTimeout(120);
            $process->run();

            if (!$process->isSuccessful() && !is_file($outputPath)) {
                $details = trim($process->getErrorOutput() ?: $process->getOutput());
                throw new RuntimeException('Impossible de generer le PDF.' . ($details !== '' ? ' ' . $details : ''));
            }

            if (!is_file($outputPath)) {
                throw new RuntimeException('Le fichier PDF n\'a pas ete genere.');
            }

            return $outputPath;
        } finally {
            $this->deleteDirectory($temporaryDirectory);
        }
    }

    private function findBrowserExecutable(): ?string
    {
        $candidates = array_filter([
            (getenv('ProgramFiles(x86)') ?: 'C:\\Program Files (x86)') . '\\Microsoft\\Edge\\Application\\msedge.exe',
            (getenv('ProgramFiles') ?: 'C:\\Program Files') . '\\Microsoft\\Edge\\Application\\msedge.exe',
        ]);

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function toFileUrl(string $path): string
    {
        return 'file:///' . str_replace('\\', '/', $path);
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
}
