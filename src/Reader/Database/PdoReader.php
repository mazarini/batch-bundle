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

namespace Mazarini\BatchBundle\Reader\Database;

use Mazarini\BatchBundle\Collection\DataCollection;
use Mazarini\BatchBundle\Collection\Record;
use Mazarini\BatchBundle\Contract\ReaderInterface;

class PdoReader implements ReaderInterface
{
    private DataCollection $dataCollection;
    /** @var array<string> */
    private array $fieldNames         = [];
    private ?\PDOStatement $statement = null;

    public function __construct(
        private \PDO $pdo,
        private string $sql
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
        $statement = $this->pdo->prepare($this->sql);
        if ($statement === false) {
            throw new \RuntimeException("Failed to prepare SQL: {$this->sql}");
        }
        $this->statement = $statement;

        $this->statement->execute();

        return $this;
    }

    public function read(): bool
    {
        if ($this->statement === null) {
            throw new \RuntimeException('Reader not opened');
        }

        $row = $this->statement->fetch(\PDO::FETCH_ASSOC);
        if ($row === false) {
            return false;
        }

        foreach ($this->fieldNames as $fieldName) {
            if (! \is_array($row)) {
                throw new \RuntimeException('Invalid row data');
            }
            $value       = $row[$fieldName] ?? '';
            $stringValue = match (true) {
                \is_string($value) => $value,
                \is_scalar($value) => (string) $value,
                default            => ''
            };
            $this->dataCollection[$fieldName]->setRawValue($stringValue);
        }

        return true;
    }

    public function close(): static
    {
        if ($this->statement !== null) {
            $this->statement->closeCursor();
            $this->statement = null;
        }

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
