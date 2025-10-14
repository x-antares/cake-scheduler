<?php

namespace App\Commands;

use App\Entities\Employee;
use App\Services\CakeScheduler;
use App\Services\DateHelper;
use App\Utils\CsvWriter;
use App\Utils\EmployeeValidator;
use App\Utils\HasErrorsTrait;
use DateTimeImmutable;

class GenerateCakeScheduleCommand
{
    use HasErrorsTrait;

    private CakeScheduler $scheduler;
    private EmployeeValidator $parser;


    public function __construct()
    {
        $this->scheduler = new CakeScheduler(new DateHelper());
        $this->parser = new EmployeeValidator();
    }

    /**
     * Generate cake schedule for employees and save it to a CSV file
     *
     * @param string $inputFile
     * @param string $outputFile
     * @param int $year
     */
    public function handle(string $inputFile, string $outputFile, int $year): void
    {
        $employees = $this->loadEmployees($inputFile);
        foreach ($employees as $employee) {
            $this->processEmployee($employee, $year);
        }

        $schedule = $this->scheduler->getSchedule();

        $writer = new CsvWriter($outputFile);
        $writer->writeHeader([
            'Date',
            'Number of Small Cakes',
            'Number of Large Cakes',
            'Names of people getting cake'
        ]);

        foreach ($schedule as $date => $data) {
            $writer->writeRow([
                $date,
                $data['small'],
                $data['large'],
                implode(', ', $data['names']),
            ]);
        }

        $writer->close();

        $this->mergeErrors($this->scheduler);
        $this->mergeErrors($writer);
        $this->mergeErrors($this->parser);
    }

    /**
     * Load employees from a plain text file.
     *
     * @param string $filePath
     * @return array
     */
    private function loadEmployees(string $filePath): array
    {
        $parser = $this->parser;
        $employees = [];

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $employee = $parser->parseLine($line);
            if ($employee !== null) {
                $employees[] = $employee;
            }
        }

        return $employees;
    }

    /**
     * Process a single employee to schedule their cake day
     *
     * @param Employee $employee
     * @param int $year
     */
    private function processEmployee(Employee $employee, int $year): void
    {
        $dob = $employee->getDateOfBirth();

        $birthdayThisYear = new DateTimeImmutable(sprintf(
            '%04d-%02d-%02d',
            $year,
            (int) $dob->format('m'),
            (int) $dob->format('d')
        ));

        $this->scheduler->allocateCakeDay($employee->getName(), $birthdayThisYear);
    }
}
