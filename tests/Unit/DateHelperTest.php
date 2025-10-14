<?php

use App\Services\DateHelper;

beforeEach(fn() => $this->helper = new DateHelper());

it('detects weekends correctly', function () {
    $sat = new DateTimeImmutable('2025-07-12');
    $sun = new DateTimeImmutable('2025-07-13');
    $mon = new DateTimeImmutable('2025-07-14');

    expect($this->helper->isWeekend($sat))->toBeTrue()
        ->and($this->helper->isWeekend($sun))->toBeTrue()
        ->and($this->helper->isWeekend($mon))->toBeFalse();
});

it('detects holidays correctly', function () {
    $newYear = new DateTimeImmutable('2025-01-01');
    $christmas = new DateTimeImmutable('2025-12-25');
    $randomDay = new DateTimeImmutable('2025-07-14');

    expect($this->helper->isHoliday($newYear))->toBeTrue()
        ->and($this->helper->isHoliday($christmas))->toBeTrue()
        ->and($this->helper->isHoliday($randomDay))->toBeFalse();
});

it('finds next workday on or after given date', function () {
    $fri = new DateTimeImmutable('2025-07-11');
    $next = $this->helper->nextWorkdayOnOrAfter($fri);
    expect($next->format('Y-m-d'))->toBe('2025-07-11');

    $sat = new DateTimeImmutable('2025-07-12');
    $next2 = $this->helper->nextWorkdayOnOrAfter($sat);
    expect($next2->format('Y-m-d'))->toBe('2025-07-14');
});

it('finds next workday after given date', function () {
    $fri = new DateTimeImmutable('2025-07-11');
    $next = $this->helper->nextWorkdayAfter($fri);
    expect($next->format('Y-m-d'))->toBe('2025-07-14');
});
