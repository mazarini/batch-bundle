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

use App\Reader\File\PersonCsvReader;
use Mazarini\BatchBundle\Collection\DataCollection;
use Mazarini\BatchBundle\Data\BooleanData;
use Mazarini\BatchBundle\Data\DateTimeData;
use Mazarini\BatchBundle\Data\DecimalData;
use Mazarini\BatchBundle\Data\IntegerData;
use Mazarini\BatchBundle\Data\StringData;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:read-persons',
    description: 'Read and display persons from CSV file'
)]
class ReadPersonsCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Reading Persons from CSV');

        // Create DataCollection with person structure
        $dataCollection               = new DataCollection();
        $dataCollection['id']         = new IntegerData();
        $dataCollection['first_name'] = new StringData();
        $dataCollection['last_name']  = new StringData();
        $dataCollection['email']      = new StringData();
        $dataCollection['age']        = new IntegerData();
        $dataCollection['birth_date'] = new DateTimeData('Y-m-d');
        $dataCollection['is_active']  = new BooleanData();
        $dataCollection['salary']     = new DecimalData();

        $dataCollection['id']->setFormat('%s');
        $dataCollection['first_name']->setFormat('%s');
        $dataCollection['last_name']->setFormat('%s');
        $dataCollection['email']->setFormat('%s');
        $dataCollection['age']->setFormat('%d');
        $dataCollection['birth_date']->setFormat('m-d-Y');
        $dataCollection['is_active']->setFormat('%d');
        $dataCollection['salary']->setFormat('%.2f');
        // Configure reader
        $reader = new PersonCsvReader('var/data/input/persons.csv');
        $reader->configure($dataCollection, ['id', 'first_name', 'last_name', 'email', 'age', 'is_active', 'salary']);

        $io->section('Person Records:');

        $count = 0;
        foreach ($reader->getRecords() as $record) {
            $id        = $dataCollection['id']->getAsInteger();
            $firstName = $dataCollection['first_name']->getAsString();
            $lastName  = $dataCollection['last_name']->getAsString();
            $email     = $dataCollection['email']->getAsString();
            $age       = $dataCollection['age']->getAsInteger();
            $isActive  = $dataCollection['is_active']->getAsBoolean() ? 'Active' : 'Inactive';
            $salary    = $dataCollection['salary']->getAsDecimal();

            $io->writeln("#{$id}: {$firstName} {$lastName} ({$email}) - Age: {$age} - Salary: €" . \number_format($salary, 2) . " - Status: {$isActive}");
            ++$count;
        }

        $io->success("Successfully read {$count} person records!");

        return Command::SUCCESS;
    }
}
