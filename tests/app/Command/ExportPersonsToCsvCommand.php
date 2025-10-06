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

use App\Writer\File\PersonCsvWriter;
use Mazarini\BatchBundle\Reader\Database\PdoReader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:export-persons-csv',
    description: 'Export persons from SQLite database to CSV file'
)]
class ExportPersonsToCsvCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Exporting Persons to CSV');

        // Create PDO connection
        $dbPath = __DIR__ . '/../../../var/data/persons.db';
        $pdo    = new \PDO("sqlite:$dbPath");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Create reader
        $sql    = 'SELECT id, first_name, last_name, email, age, is_active, salary FROM persons ORDER BY id';
        $reader = new PdoReader($pdo, $sql);

        // Create writer and process
        $outputPath = __DIR__ . '/../../../var/data/output/persons_export.csv';
        $writer     = new PersonCsvWriter($outputPath, $reader);
        $writer->process();

        $io->success("Successfully exported persons to: $outputPath");

        return Command::SUCCESS;
    }
}
