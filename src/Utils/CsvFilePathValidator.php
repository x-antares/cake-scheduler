<?php

namespace App\Utils;

class CsvFilePathValidator
{
    use HasErrorsTrait;

    private string $filePath;

    /**
     * CsvFileValidator constructor.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = trim($filePath);
        $this->validatePathString();
    }

    /**
     * Returns the validated and possibly corrected file path string
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * Validate the file path string without touching the filesystem
     */
    private function validatePathString(): void
    {
        if ($this->filePath === '') {
            $this->addError("File path cannot be empty");
            return;
        }

        if (str_ends_with($this->filePath, '/') || str_ends_with($this->filePath, '\\')) {
            $this->addError("File path should not be a directory: {$this->filePath}");
        }

        if (preg_match('/[<>:"|?*]/', $this->filePath)) {
            $this->addError("File path contains invalid characters: {$this->filePath}");
        }

        if ((count($this->getErrors()) === 0) && strtolower(pathinfo($this->filePath, PATHINFO_EXTENSION)) !== 'csv') {
            $this->filePath .= '.csv';
        }
    }
}
