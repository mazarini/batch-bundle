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

abstract class FixedWriter implements WriterInterface
{
    /** @var resource|null */
    private $handle;

    public function __construct(
        private string $filePath,
        private ReaderInterface $reader
    ) {
    }

    public function process(): static
    {
        $dataCollection = $this->createDataCollection();
        $requiredFields = $dataCollection->getKeys();

        $this->reader->configure($dataCollection, $requiredFields);

        $this->open();

        foreach ($this->reader->getRecords() as $record) {
            $this->writeDataRow($dataCollection);
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

    private function writeDataRow(DataCollection $dataCollection): void
    {
        if ($this->handle === null) {
            throw new \RuntimeException('Writer not opened');
        }

        $structure = $this->getStructure();
        $line      = '';

        foreach ($structure as $fieldName => $config) {
            $value = $dataCollection[$fieldName]->getRawValue();

            // Handle special formats
            if (\str_contains($config['format'], '|no_dot')) {
                $format         = \str_replace('|no_dot', '', $config['format']);
                $formattedValue = \str_replace('.', '', \sprintf($format, $value));
            } else {
                $formattedValue = \sprintf($config['format'], $value);
            }

            $line .= $formattedValue;
        }

        \fwrite($this->handle, $line . "\n");
    }

    /**
     * Create the DataCollection with required data types.
     */
    abstract protected function createDataCollection(): DataCollection;

    /**
     * Get the fixed-width structure definition.
     *
     * @return array<string, array{format: string}>
     */
    abstract protected function getStructure(): array;
}
