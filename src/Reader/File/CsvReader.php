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

namespace Mazarini\BatchBundle\Reader\File;

use Mazarini\BatchBundle\Collection\DataCollection;
use Mazarini\BatchBundle\Collection\ObjectCollection;
use Mazarini\BatchBundle\Collection\Record;
use Mazarini\BatchBundle\Contract\ReaderInterface;
use Mazarini\BatchBundle\Field\CsvField;

/**
 * @internal This class is internal to the BatchBundle
 */
abstract class CsvReader implements ReaderInterface
{
    private DataCollection $dataCollection;
    /** @var array<string> */
    private array $fieldNames = [];
    /** @var resource|null */
    private $handle;
    /** @var ObjectCollection<CsvField> */
    private ObjectCollection $record;
    protected bool $hasHeaderRow = true;

    public function __construct(
        private string $filePath,
        private string $delimiter = ',',
        private string $enclosure = '"'
    ) {
        $this->record = new ObjectCollection();
    }

    /**
     * getStructure.
     *
     * @return array<string, int> Array of field names and their positions
     */
    abstract public function getStructure(): array;

    public function configure(DataCollection $dataCollection, array $fieldNames): static
    {
        $this->dataCollection = $dataCollection;
        $this->fieldNames     = $fieldNames;
        $structure            = $this->getStructure();

        foreach ($fieldNames as $fieldName) {
            if (! isset($this->dataCollection[$fieldName])) {
                throw new \InvalidArgumentException("Field '{$fieldName}' does not exist in DataCollection.");
            }
            $data = $dataCollection[$fieldName];
            if (! isset($structure[$fieldName])) {
                throw new \InvalidArgumentException("Field '{$fieldName}' does not exist in csvfield.");
            }
            $position                 = $this->getStructure()[$fieldName] ?? null;
            $this->record[$fieldName] = new CsvField($data, $position);
        }

        return $this;
    }

    public function open(): static
    {
        $handle = \fopen($this->filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Cannot open file: {$this->filePath}");
        }
        $this->handle = $handle;

        return $this;
    }

    public function read(): bool
    {
        if ($this->handle === null) {
            throw new \RuntimeException('Reader not opened');
        }

        $row = \fgetcsv($this->handle, 0, $this->delimiter, $this->enclosure);
        if ($row === false) {
            return false;
        }

        foreach ($this->fieldNames as $fieldName) {
            $field    = $this->record[$fieldName];
            $position = $field->getPosition();
            $field->getData()->setRawValue($row[$position]);
        }

        return true;
    }

    public function close(): static
    {
        if ($this->handle !== null) {
            \fclose($this->handle);
            $this->handle = null;
        }

        return $this;
    }

    public function getRecords(): \Generator
    {
        $this->open();

        if ($this->hasHeaderRow) {
            // Skip header row
            \fgetcsv($this->handle, 0, $this->delimiter, $this->enclosure);
        }

        try {
            while ($this->read()) {
                $recordData = [];
                foreach ($this->record as $key => $field) {
                    $recordData[$key] = $field->getData();
                }
                yield new Record($recordData);
            }
        } finally {
            $this->close();
        }
    }
}
