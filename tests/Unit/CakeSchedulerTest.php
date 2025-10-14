<?php

use App\Services\CakeScheduler;
use App\Services\DateHelper;

beforeEach(fn() => $this->scheduler = new CakeScheduler(new DateHelper()));

it('respects cake-free day', function () {
    $this->scheduler->allocateCakeDay('Alice', new DateTimeImmutable('2025-07-14'));
    $this->scheduler->allocateCakeDay('Bob', new DateTimeImmutable('2025-07-15'));

    $schedule = $this->scheduler->getSchedule();
    $dates = array_keys($schedule);

    expect($dates[0])->toBe('2025-07-15')
        ->and($dates[1])->toBe('2025-07-17');
});

it('marks second cake as large if same day', function () {
    $this->scheduler->allocateCakeDay('Alice', new DateTimeImmutable('2025-07-14'));
    $this->scheduler->allocateCakeDay('Bob', new DateTimeImmutable('2025-07-14'));

    $schedule = $this->scheduler->getSchedule();
    $date = array_keys($schedule)[0];

    expect($schedule[$date]['small'])->toBe(0)
        ->and($schedule[$date]['large'])->toBe(1)
        ->and($schedule[$date]['names'])->toContain('Alice')
        ->and($schedule[$date]['names'])->toContain('Bob');
});

