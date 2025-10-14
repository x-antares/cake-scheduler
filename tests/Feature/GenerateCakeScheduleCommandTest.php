<?php

namespace Tests\Feature;

use App\Commands\GenerateCakeScheduleCommand;

beforeEach(function () {
    $this->tmpInput = __DIR__ . '/employees_sample.txt';
    $this->tmpOutput = __DIR__ . '/cakes_sample.csv';

    file_put_contents($this->tmpInput, "Alice,1990-07-15\n");

    $this->command = new GenerateCakeScheduleCommand();
});

afterEach(function () {
    if (file_exists($this->tmpInput)) {
        unlink($this->tmpInput);
    }
    if (file_exists($this->tmpOutput)) {
        unlink($this->tmpOutput);
    }
});
it('generates a CSV file for employees without errors', function () {
    $year = 2025;

    $this->command->handle($this->tmpInput, $this->tmpOutput, $year);

    expect(file_exists($this->tmpOutput))->toBeTrue();

    $rows = [];
    if (($handle = fopen($this->tmpOutput, 'r')) !== false) {
        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = $data;
        }
        fclose($handle);
    }

    expect($rows[0])->toBe([
        'Date',
        'Number of Small Cakes',
        'Number of Large Cakes',
        'Names of people getting cake'
    ]);

    $found = false;
    foreach ($rows as $row) {
        if (isset($row[3]) && str_contains($row[3], 'Alice')) {
            $found = true;
            break;
        }
    }
    expect($found)->toBeTrue()
        ->and($this->command->hasErrors())->toBeFalse();
});
