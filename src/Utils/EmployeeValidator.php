<?php

namespace App\Utils;

use App\Entities\Employee;
use DateTimeImmutable;

class EmployeeValidator
{
    use HasErrorsTrait;

    /**
     * Parse a single line and return Employee or null if invalid
     *
     * @param string $line
     * @return Employee|null
     */
    public function parseLine(string $line): ?Employee
    {
        $parts = array_map('trim', explode(',', $line));

        if (count($parts) < 2) {
            $this->addError("Invalid line format: '$line'");
            return null;
        }

        [$name, $dob] = $parts;

        if ($this->isEmptyDate($dob)) {
            $this->addError("Missing date for employee '$name'");
            return null;
        }

        if ($this->isZeroDate($dob)) {
            $this->addError("Invalid date '$dob' for employee '$name'");
            return null;
        }

        if (!$this->isValidDateFormat($dob)) {
            $this->addError("Invalid date format '$dob' for employee '$name'");
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('Y-m-d', $dob);

        return new Employee($name, $date);
    }

    /**
     * @param string|null $dob
     * @return bool
     */
    private function isEmptyDate(?string $dob): bool
    {
        return $dob === null || $dob === '';
    }

    /**
     * @param string $dob
     * @return bool
     */
    private function isZeroDate(string $dob): bool
    {
        return $dob === '0000-00-00';
    }

    /**
     * @param string $dob
     * @return bool
     */
    private function isValidDateFormat(string $dob): bool
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob) === 1;
    }

    /**
     * @param string $message
     */
    private function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
