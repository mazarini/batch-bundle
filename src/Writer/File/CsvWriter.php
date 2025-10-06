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

namespace Mazarini\BatchBundle\Writer\File;

use Mazarini\BatchBundle\Collection\DataCollection;
use Mazarini\BatchBundle\Contract\ReaderInterface;
use Mazarini\BatchBundle\Contract\WriterInterface;

abstract class CsvWriter implements WriterInterface
{
    /** @var resource|null */
    private $handle;

    public function __construct(
        private string $filePath,
        private ReaderInterface $reader,
        private string $delimiter = ',',
        private string $enclosure = '"',
        private bool $writeHeader = true
    ) {
    }

    public function process(): static
    {
        $dataCollection = $this->createDataCollection();
        $requiredFields = $dataCollection->getKeys();

        $this->reader->configure($dataCollection, $requiredFields);

        $this->open();

        if ($this->writeHeader) {
            $this->writeHeaderRow($requiredFields);
        }

        foreach ($this->reader->getRecords() as $record) {
            $this->writeDataRow($requiredFields, $dataCollection);
        }

        $this->close();

        return $this;
    }

    private function open(): void
    {
        $handle = \fopen($this->filePath, 'w');
        if ($handle === false) {
            throw new \RuntimeException("Cannot open file: {$this->filePath}");
        }
        $this->handle = $handle;
    }

    private function close(): void
    {
        if ($this->handle !== null) {
            \fclose($this->handle);
            $this->handle = null;
        }
    }

    /**
     * @param array<string> $fieldNames
     */
    private function writeHeaderRow(array $fieldNames): void
    {
        if ($this->handle === null) {
            throw new \RuntimeException('Writer not opened');
        }

        \fputcsv($this->handle, $fieldNames, $this->delimiter, $this->enclosure);
    }

    /**
     * @param array<string> $fieldNames
     */
    private function writeDataRow(array $fieldNames, DataCollection $dataCollection): void
    {
        if ($this->handle === null) {
            throw new \RuntimeException('Writer not opened');
        }

        $row = [];
        foreach ($fieldNames as $fieldName) {
            $row[] = $dataCollection[$fieldName]->getRawValue();
        }

        \fputcsv($this->handle, $row, $this->delimiter, $this->enclosure);
    }

    /**
     * Create the DataCollection with required data types.
     */
    abstract protected function createDataCollection(): DataCollection;
}
