<?php

namespace App\Entities;

use DateTimeImmutable;

readonly class Employee
{
    public function __construct(
        private string            $name,
        private DateTimeImmutable $dateOfBirth
    ) {}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDateOfBirth(): DateTimeImmutable
    {
        return $this->dateOfBirth;
    }
}
