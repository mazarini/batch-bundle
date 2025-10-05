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
use Mazarini\BatchBundle\Collection\Record;
use Mazarini\BatchBundle\Contract\ReaderInterface;

class DirectoryReader implements ReaderInterface
{
    private DataCollection $dataCollection;
    /** @var array<string> */
    private array $fieldNames = [];
    /** @var \Iterator<\SplFileInfo> */
    private \Iterator $iterator;

    public function __construct(
        private string $directoryPath,
        private string $pattern = '*'
    ) {
    }

    public function configure(DataCollection $dataCollection, array $fieldNames): static
    {
        $this->dataCollection = $dataCollection;
        $this->fieldNames     = $fieldNames;

        return $this;
    }

    public function open(): static
    {
        if (! \is_dir($this->directoryPath)) {
            throw new \RuntimeException("Directory does not exist: {$this->directoryPath}");
        }

        $this->iterator = new \GlobIterator($this->directoryPath . '/' . $this->pattern);

        return $this;
    }

    public function read(): bool
    {
        if (! $this->iterator->valid()) {
            return false;
        }

        $fileInfo = $this->iterator->current();

        foreach ($this->fieldNames as $fieldName) {
            $value = match ($fieldName) {
                'filename'  => $fileInfo->getFilename(),
                'basename'  => $fileInfo->getBasename(),
                'path'      => $fileInfo->getPathname(),
                'size'      => (string) $fileInfo->getSize(),
                'modified'  => \date('Y-m-d H:i:s', $fileInfo->getMTime()),
                'extension' => $fileInfo->getExtension(),
                'is_file'   => $fileInfo->isFile() ? '1' : '0',
                'is_dir'    => $fileInfo->isDir() ? '1' : '0',
                default     => ''
            };

            $this->dataCollection[$fieldName]->setRawValue($value);
        }

        $this->iterator->next();

        return true;
    }

    public function close(): static
    {
        return $this;
    }

    public function getRecords(): \Generator
    {
        $this->open();

        try {
            while ($this->read()) {
                $recordData = [];
                foreach ($this->fieldNames as $fieldName) {
                    $recordData[$fieldName] = clone $this->dataCollection[$fieldName];
                }
                yield new Record($recordData);
            }
        } finally {
            $this->close();
        }
    }
}
