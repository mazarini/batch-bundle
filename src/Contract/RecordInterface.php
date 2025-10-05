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

namespace Mazarini\BatchBundle\Contract;

/**
 * Defines the contract for a record holding DataInterface objects.
 * A record represents a single row of data (like a CSV row or database record).
 *
 * @extends \IteratorAggregate<string, DataInterface>
 * @extends \ArrayAccess<string, DataInterface>
 *
 * @internal This interface is internal to the BatchBundle
 */
interface RecordInterface extends \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * Adds a DataInterface object to the record with the specified key.
     */
    public function add(string $key, DataInterface $data): static;

    /**
     * Retrieves a DataInterface object by key.
     */
    public function get(string $key): ?DataInterface;

    /**
     * Checks if a key exists in the record.
     */
    public function has(string $key): bool;

    /**
     * Removes a data object from the record.
     */
    public function remove(string $key): static;

    /**
     * Returns all keys in the record.
     *
     * @return array<string>
     */
    public function getKeys(): array;

    /**
     * Checks if the record is empty.
     */
    public function isEmpty(): bool;

    /**
     * Clears all data objects from the record.
     */
    public function clear(): static;
}
