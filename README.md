# Cake Scheduler

**Cake Scheduler** is a CLI tool for generating a cake schedule for employees, taking into account birthdays, workdays, and "cake-free" days after each cake.

---

## Requirements

- PHP ^8.2
- Composer 2
---

## Installation

1. Clone/copy project repository.

2. Run:
```bash
composer install
```

## Usage
Generate a cake schedule via CLI

```bash
php bin/cake-scheduler --input=<input_file> --output=<output_file> --year=<year>
```


Parameters:
```bash
--input - path to a text file containing employee data (Name,YYYY-MM-DD per line)

--output - path to the CSV file where the schedule will be saved

--year - the year for which to generate the schedule
```


Example:

bin/cake-scheduler --input=data/employees.txt --output=output/cakes.csv --year=2025

Test data path:

data/employees.txt


## Tests
```bash
composer test
```

