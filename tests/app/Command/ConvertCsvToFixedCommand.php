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
use App\Writer\File\PersonFixedWriter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:convert-csv-to-fixed',
    description: 'Convert persons CSV file to fixed-width format'
)]
class ConvertCsvToFixedCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Converting CSV to Fixed-Width Format');

        // Create CSV reader
        $inputPath = __DIR__ . '/../../../var/data/input/persons.csv';
        $reader    = new PersonCsvReader($inputPath);

        // Create Fixed writer and process
        $outputPath = __DIR__ . '/../../../var/data/output/persons_converted.fixed';
        $writer     = new PersonFixedWriter($outputPath, $reader);
        $writer->process();

        $io->success("Successfully converted CSV to fixed-width format: $outputPath");

        return Command::SUCCESS;
    }
}
