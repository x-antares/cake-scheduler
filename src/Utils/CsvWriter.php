<?php

namespace App\Utils;

class CsvWriter
{
    use HasErrorsTrait;

    private $handle;

    public function __construct(string $filePath)
    {
        try {
            $this->handle = fopen($filePath, 'w');
            if (!$this->handle) {
                $this->addError("Cannot open file for writing: $filePath");
            }
        } catch (\Throwable $e) {
            $this->addError("Failed to open file '$filePath': " . $e->getMessage());
        }
    }

    /**
     * @param array $columns
     */
    public function writeHeader(array $columns): void
    {
        if (!is_resource($this->handle)) {
            return;
        }
        fputcsv($this->handle, $columns);
    }

    /**
     * @param array $row
     */
    public function writeRow(array $row): void
    {
        if (!is_resource($this->handle)) {
            return;
        }
        fputcsv($this->handle, $row);
    }

    public function close(): void
    {
        if (is_resource($this->handle)) {
            fclose($this->handle);
        }
    }
}
