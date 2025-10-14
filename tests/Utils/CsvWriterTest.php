<?php

use App\Utils\CsvWriter;
use App\Utils\CsvFilePathValidator;

it('creates file with proper extension', function () {
    $validator = new CsvFilePathValidator('test_output');
    expect($validator->getFilePath())->toEndWith('.csv');
});

it('writes header and row', function () {
    $file = __DIR__ . '/csv_writer_test.csv';
    $writer = new CsvWriter($file);
    $writer->writeHeader(['A', 'B']);
    $writer->writeRow(['1', '2']);
    $writer->close();

    $contents = file_get_contents($file);
    expect($contents)->toContain('A,B')
        ->and($contents)->toContain('1,2');

    if (file_exists($file)) {
        unlink($file);
    }
});
