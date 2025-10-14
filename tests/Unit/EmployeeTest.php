<?php

use App\Entities\Employee;
use App\Utils\EmployeeValidator;

it('creates employee correctly', function () {
    $dob = new DateTimeImmutable('1990-05-12');
    $emp = new Employee('Alice', $dob);
    expect($emp->getName())->toBe('Alice')
        ->and($emp->getDateOfBirth()->format('Y-m-d'))->toBe('1990-05-12');
});

it('validates correct line', function () {
    $validator = new EmployeeValidator();
    $emp = $validator->parseLine('Bob,1985-07-01');
    expect($emp)->toBeInstanceOf(Employee::class)
        ->and($emp->getName())->toBe('Bob');
});

it('collects errors for invalid lines', function () {
    $validator = new EmployeeValidator();
    expect($validator->parseLine(''))->toBeNull()
        ->and($validator->getErrors())->not->toBeEmpty()
        ->and($validator->parseLine('Charlie,0000-00-00'))->toBeNull()
        ->and($validator->getErrors())->not->toBeEmpty()
        ->and($validator->parseLine('Diana,invalid-date'))->toBeNull()
        ->and($validator->getErrors())->not->toBeEmpty();

});
