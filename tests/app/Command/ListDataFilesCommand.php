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
use Mazarini\BatchBundle\Data\IntegerData;
use Mazarini\BatchBundle\Data\StringData;
use Mazarini\BatchBundle\Reader\File\DirectoryReader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:list-data-files',
    description: 'List files in var/data directory'
)]
class ListDataFilesCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Listing Data Files');

        // Setup data collection
        $dataCollection = new DataCollection();
        $dataCollection->add('filename', new StringData());
        $dataCollection->add('size', new IntegerData());
        $dataCollection->add('modified', new StringData());
        $dataCollection->add('is_file', new BooleanData());

        // Configure reader
        $directoryPath = __DIR__ . '/../../../var/data';
        $reader        = new DirectoryReader($directoryPath);
        $reader->configure($dataCollection, ['filename', 'size', 'modified', 'is_file']);

        $io->section('Files in var/data:');

        $count = 0;
        foreach ($reader->getRecords() as $record) {
            $filename = $record['filename']->getAsString();
            $size     = $record['size']->getAsInteger();
            $modified = $record['modified']->getAsString();
            $isFile   = $record['is_file']->getAsBoolean();

            if ($isFile) {
                $sizeFormatted = \number_format($size) . ' bytes';
                $io->writeln("📄 {$filename} - {$sizeFormatted} - Modified: {$modified}");
                ++$count;
            }
        }

        $io->success("Found {$count} files in var/data directory!");

        return Command::SUCCESS;
    }
}
