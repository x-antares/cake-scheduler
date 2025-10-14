<?php

namespace App\Utils;

trait HasErrorsTrait
{
    /**
     * Collected errors
     *
     * @var array
     */
    private array $errors = [];

    /**
     * Add a single error
     *
     * @param string $error
     */
    protected function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * Get all collected errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Clear all collected errors
     */
    protected function clearErrors(): void
    {
        $this->errors = [];
    }

    /**
     * Merge errors from another source
     *
     * @param array|object $source
     */
    protected function mergeErrors(array|object $source): void
    {
        if (is_array($source)) {
            $this->errors = array_merge($this->errors, $source);
        } elseif (is_object($source) && method_exists($source, 'getErrors')) {
            $this->errors = array_merge($this->errors, $source->getErrors());
        }
    }

    /**
     * Check if there are any collected errors
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
