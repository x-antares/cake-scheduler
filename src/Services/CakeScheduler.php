<?php

namespace App\Services;

use App\Utils\HasErrorsTrait;
use DateTimeImmutable;

class CakeScheduler
{
    use HasErrorsTrait;

    private DateHelper $helper;
    private array $schedule = [];
    private array $cakeFreeDays = [];

    public function __construct(DateHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Schedule an employee's cake day respecting workdays and cake-free rules
     *
     * @param string $employeeName
     * @param DateTimeImmutable $birthday
     */
    public function allocateCakeDay(string $employeeName, DateTimeImmutable $birthday): void
    {
        $dayOff = $this->helper->nextWorkdayOnOrAfter($birthday);
        $cakeDay = $this->helper->nextWorkdayAfter($dayOff);

        while (isset($this->cakeFreeDays[$cakeDay->format('Y-m-d')])) {
            $cakeDay = $this->helper->nextWorkdayAfter($cakeDay);
        }

        $dateStr = $cakeDay->format('Y-m-d');

        if (isset($this->schedule[$dateStr])) {
            $this->schedule[$dateStr]['large'] = 1;
            $this->schedule[$dateStr]['small'] = 0;
            $this->schedule[$dateStr]['names'][] = $employeeName;
        } else {
            $this->schedule[$dateStr] = [
                'small' => 1,
                'large' => 0,
                'names' => [$employeeName],
            ];
        }

        $next = $cakeDay->add(new \DateInterval('P1D'));
        $nextWorkday = $this->helper->nextWorkdayOnOrAfter($next);
        $this->cakeFreeDays[$nextWorkday->format('Y-m-d')] = true;

        $this->mergeErrors($this->helper);
    }


    /**
     * Return the full cake schedule sorted by date
     *
     * @return array
     */
    public function getSchedule(): array
    {
        ksort($this->schedule);
        return $this->schedule;
    }
}
