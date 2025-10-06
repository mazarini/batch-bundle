<?php

declare(strict_types=1);

// Script to create SQLite database with persons table

$dbPath = __DIR__ . '/persons.db';

// Remove existing database
if (file_exists($dbPath)) {
    unlink($dbPath);
}

// Create SQLite connection
$pdo = new PDO("sqlite:$dbPath");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create persons table
$createTable = "
CREATE TABLE persons (
    id INTEGER PRIMARY KEY,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    email TEXT NOT NULL,
    age INTEGER NOT NULL,
    birth_date TEXT NOT NULL,
    is_active INTEGER NOT NULL,
    salary REAL NOT NULL
)";

$pdo->exec($createTable);

// Insert data from CSV
$insertSql = "INSERT INTO persons (id, first_name, last_name, email, age, birth_date, is_active, salary) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($insertSql);

$persons = [
    [1, 'John', 'Doe', 'john.doe@example.com', 30, '1993-05-15', 1, 45000.50],
    [2, 'Jane', 'Smith', 'jane.smith@example.com', 25, '1998-12-03', 1, 38000.00],
    [3, 'Bob', 'Johnson', 'bob.johnson@example.com', 45, '1978-08-22', 0, 65000.75],
    [4, 'Alice', 'Brown', 'alice.brown@example.com', 28, '1995-11-07', 1, 42500.25],
    [5, 'Charlie', 'Wilson', 'charlie.wilson@example.com', 35, '1988-03-14', 1, 55000.00],
    [6, 'Diana', 'Davis', 'diana.davis@example.com', 22, '2001-09-30', 0, 32000.50],
    [7, 'Frank', 'Miller', 'frank.miller@example.com', 52, '1971-06-18', 1, 78000.00],
    [8, 'Grace', 'Taylor', 'grace.taylor@example.com', 29, '1994-01-25', 1, 41000.75],
    [9, 'Henry', 'Anderson', 'henry.anderson@example.com', 41, '1982-10-12', 0, 58500.25],
    [10, 'Ivy', 'Thomas', 'ivy.thomas@example.com', 33, '1990-07-04', 1, 47500.00],
];

foreach ($persons as $person) {
    $stmt->execute($person);
}

echo "SQLite database created successfully at: $dbPath\n";
echo "Table 'persons' created with " . count($persons) . " records.\n";