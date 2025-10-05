<?php

declare(strict_types=1);

/*
 * Copyright (C) 2025 Mazarini <mazarini@pm.me>.
 * This file is part of mazarini/batch-bundle.
 *
 * mazarini/batch-bundle is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.
 *
 * mazarini/batch-bundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with mazarini/batch-bundle. If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Command;

use Mazarini\BatchBundle\Collection\DataCollection;
use Mazarini\BatchBundle\Data\BooleanData;
use Mazarini\BatchBundle\Data\DecimalData;
use Mazarini\BatchBundle\Data\IntegerData;
use Mazarini\BatchBundle\Data\StringData;
use Mazarini\BatchBundle\Reader\Database\PdoReader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:read-persons-database',
    description: 'Read persons from SQLite database and display them'
)]
class ReadPersonsDatabaseCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Reading Persons from SQLite Database');

        // Setup data collection
        $dataCollection = new DataCollection();
        $dataCollection->add('id', new IntegerData());
        $dataCollection->add('first_name', new StringData());
        $dataCollection->add('last_name', new StringData());
        $dataCollection->add('email', new StringData());
        $dataCollection->add('age', new IntegerData());
        $dataCollection->add('is_active', new BooleanData());
        $dataCollection->add('salary', new DecimalData());

        // Create PDO connection
        $dbPath = __DIR__ . '/../../../var/data/persons.db';
        $pdo    = new \PDO("sqlite:$dbPath");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Configure reader
        $sql    = 'SELECT id, first_name, last_name, email, age, is_active, salary FROM persons ORDER BY id';
        $reader = new PdoReader($pdo, $sql);
        $reader->configure($dataCollection, ['id', 'first_name', 'last_name', 'email', 'age', 'is_active', 'salary']);

        $io->section('Person Records:');

        $count = 0;
        foreach ($reader->getRecords() as $record) {
            $id        = $record['id']->getAsInteger();
            $firstName = $record['first_name']->getAsString();
            $lastName  = $record['last_name']->getAsString();
            $email     = $record['email']->getAsString();
            $age       = $record['age']->getAsInteger();
            $isActive  = $record['is_active']->getAsBoolean();
            $salary    = $record['salary']->getAsDecimal();

            $status = $isActive ? 'Active' : 'Inactive';
            $io->writeln("#{$id}: {$firstName} {$lastName} ({$email}) - Age: {$age} - Salary: €" . \number_format($salary, 2) . " - Status: {$status}");
            ++$count;
        }

        $io->success("Successfully read {$count} person records from SQLite database!");

        return Command::SUCCESS;
    }
}
