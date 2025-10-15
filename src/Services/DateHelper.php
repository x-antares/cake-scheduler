<?php

namespace App\Services;

use DateInterval;
use DateTimeImmutable;

class DateHelper
{
    private const HOLIDAYS = ['01-01', '12-25', '12-26'];
    private static array $holidayCache = [];

    /**
     * Check if the given date is a weekend
     *
     * @param DateTimeImmutable $date
     * @return bool
     */
    public function isWeekend(DateTimeImmutable $date): bool
    {
        return (int) $date->format('N') >= 6;
    }

    /**
     * Check if the given date is a holiday
     *
     * @param DateTimeImmutable $date
     * @return bool
     */
    public function isHoliday(DateTimeImmutable $date): bool
    {
        $key = $date->format('Y-m-d');

        if (!isset(self::$holidayCache[$key])) {
            self::$holidayCache[$key] = in_array($date->format('m-d'), self::HOLIDAYS, true);
        }

        return self::$holidayCache[$key];
    }

    /**
     * Check if the given date is a workday
     *
     * @param DateTimeImmutable $date
     * @return bool
     */
    public function isWorkday(DateTimeImmutable $date): bool
    {
        return !$this->isWeekend($date) && !$this->isHoliday($date);
    }

    /**
     * Returns the next workday after the given date, skipping weekends and holidays
     *
     * @param DateTimeImmutable $date
     * @return DateTimeImmutable
     */
    public function nextWorkdayAfter(DateTimeImmutable $date): DateTimeImmutable
    {
        $current = $date->add(new DateInterval('P1D'));

        while (!$this->isWorkday($current)) {
            $current = $current->add(new DateInterval('P1D'));
        }

        return $current;
    }

    /**
     * Returns the next workday on or after the given date, skipping weekends and holidays
     *
     * @param DateTimeImmutable $date
     * @return DateTimeImmutable
     */
    public function nextWorkdayOnOrAfter(DateTimeImmutable $date): DateTimeImmutable
    {
        $current = $date;

        while (!$this->isWorkday($current)) {
            $current = $current->add(new DateInterval('P1D'));
        }

        return $current;
    }
}
