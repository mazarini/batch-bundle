# mazarini/batch-bundle – Typed Data Processing Pipeline

A PHP/Symfony framework for reading, transforming, and writing structured data with type safety. Supports CSV files, databases, APIs, and more through a unified pipeline architecture.

## 🚀 Quick Start

```php
use Mazarini\BatchBundle\Collection\DataCollection;
use Mazarini\BatchBundle\Data\{IntegerData, StringData, DecimalData};
use App\Reader\File\PersonCsvReader;

// 1. Define data structure
$dataCollection = new DataCollection();
$dataCollection->add('id', new IntegerData());
$dataCollection->add('name', new StringData());
$dataCollection->add('salary', new DecimalData());

// 2. Configure reader
$reader = new PersonCsvReader('data/persons.csv');
$reader->configure($dataCollection, ['id', 'name', 'salary']);

// 3. Process data
foreach ($reader->getRecords() as $record) {
    $id = $record['id']->getData()->getAsInteger();
    $name = $record['name']->getData()->getAsString();
    $salary = $record['salary']->getData()->getAsDecimal();
    
    echo "Person #{$id}: {$name} - Salary: €{$salary}\n";
}
```

## 🏗️ Architecture

### Core Components

- **DataInterface** - Contract for typed data objects
- **DataCollection** - Container for all data objects (Working Storage)
- **Record** - Subset of data for processing (like a CSV row)
- **Field** - Wrapper for data with metadata (CSV position, etc.)
- **Reader** - Abstraction for data sources (CSV, DB, API)

### Data Types

| Type | PHP Type | Description |
|------|----------|-------------|
| `IntegerData` | `int` | Whole numbers with configurable filters |
| `DecimalData` | `float` | Floating-point numbers |
| `StringData` | `string` | Text data |
| `BooleanData` | `bool` | True/false values |
| `DateTimeData` | `DateTimeImmutable` | Dates with custom formats |

### Type Safety Features

- **Raw string storage** - All data stored as strings internally
- **Type conversion on demand** - `getAsInteger()`, `getAsString()`, etc.
- **Null handling** - `getAsIntegerOrNull()`, `setAsStringOrNull()`
- **Format customization** - `setFormat('%05d')`, `setFormat('%.2f')`
- **Validation** - Built-in filters and range checking

## 📁 Project Structure

```
src/
├── Contract/           # Interfaces
│   ├── DataInterface.php
│   ├── DataCollectionInterface.php
│   ├── RecordInterface.php
│   └── ReaderInterface.php
├── Data/              # Typed data classes
│   ├── DataAbstract.php
│   ├── IntegerData.php
│   ├── StringData.php
│   ├── DecimalData.php
│   ├── BooleanData.php
│   └── DateTimeData.php
├── Collection/        # Data containers
│   ├── ObjectCollection.php
│   ├── DataCollection.php
│   └── Record.php
├── Field/            # Field wrappers
│   ├── Field.php
│   └── CsvField.php
├── Reader/           # Data source readers
│   └── File/
│       └── CsvReader.php
└── Enum/
    └── TypeEnum.php

app/                  # Application-specific implementations
├── Reader/
│   └── File/
│       └── PersonCsvReader.php
└── Command/
    └── ReadPersonsCommand.php
```

## 🔧 Creating Custom Readers

### 1. Define CSV Structure

```php
namespace App\Reader\File;

use Mazarini\BatchBundle\Reader\File\CsvReader;

class PersonCsvReader extends CsvReader
{
    public function getStructure(): array
    {
        return [
            'id' => 0,
            'first_name' => 1,
            'last_name' => 2,
            'email' => 3,
            'salary' => 4,
        ];
    }
}
```

### 2. Configure Data Types

```php
$dataCollection = new DataCollection();
$dataCollection->add('id', new IntegerData());
$dataCollection->add('first_name', new StringData());
$dataCollection->add('salary', new DecimalData());

// Optional: Set custom formats
$dataCollection->get('id')->setFormat('%05d');
$dataCollection->get('salary')->setFormat('%.2f');
```

### 3. Process Data

```php
$reader = new PersonCsvReader('/path/to/data.csv');
$reader->configure($dataCollection, ['id', 'first_name', 'salary']);

foreach ($reader->getRecords() as $record) {
    // Type-safe access
    $id = $record['id']->getData()->getAsInteger();
    $name = $record['first_name']->getData()->getAsString();
    $salary = $record['salary']->getData()->getAsDecimal();
}
```

## 🎯 Advanced Features

### Custom Filters and Validation

```php
// Integer with range validation
$ageData = new IntegerData(FILTER_VALIDATE_INT, 0, 120);

// Custom date format
$birthDate = new DateTimeData('d/m/Y');

// Decimal with precision
$price = new DecimalData();
$price->setFormat('%.2f');
```

### Memory Efficient Processing

```php
// Generator-based reading for large files
foreach ($reader->getRecords() as $record) {
    // Process one record at a time
    // Memory usage stays constant
}
```

### Working Storage Pattern

```php
// DataCollection acts as COBOL-like Working Storage
$workingStorage = new DataCollection();
$workingStorage->add('counter', new IntegerData());
$workingStorage->add('total', new DecimalData());

// Reset all values while keeping structure
$workingStorage->reset();
```

## 🧪 Testing

Run the example command:

```bash
php bin/console app:read-persons
```

Expected output:
```
Reading Persons from CSV
========================

Person Records:
---------------
#1: John Doe (john.doe@example.com) - Age: 30 - Salary: €45,000.50 - Status: Active
#2: Jane Smith (jane.smith@example.com) - Age: 25 - Salary: €38,000.00 - Status: Active
...

[OK] Successfully read 10 person records!
```

## 📋 Requirements

- PHP 8.1+
- Symfony 6.0+

## 📄 License

This project is licensed under the GNU General Public License v3.0 - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

**Built with ❤️ and with help of Amazon Q Developer for robust data processing pipelines**