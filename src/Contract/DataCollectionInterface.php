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
 * Defines the contract for a collection holding DataInterface objects in the pipeline.
 * This collection manages all data objects used during batch processing.
 *
 * @extends \IteratorAggregate<string, DataInterface>
 * @extends \ArrayAccess<string, DataInterface>
 *
 * @internal This interface is internal to the BatchBundle
 */
interface DataCollectionInterface extends \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * Adds a DataInterface object to the collection with the specified key.
     */
    public function add(string $key, DataInterface $data): static;

    /**
     * Retrieves a DataInterface object by key.
     */
    public function get(string $key): ?DataInterface;

    /**
     * Checks if a key exists in the collection.
     */
    public function has(string $key): bool;

    /**
     * Removes a data object from the collection.
     */
    public function remove(string $key): static;

    /**
     * Returns all keys in the collection.
     *
     * @return array<string>
     */
    public function getKeys(): array;

    /**
     * Checks if the collection is empty.
     */
    public function isEmpty(): bool;

    /**
     * Clears all data objects from the collection.
     */
    public function clear(): static;

    /**
     * Resets all DataInterface objects in the collection to their initial state.
     * Calls reset() on each DataInterface object without removing them from the collection.
     */
    public function reset(): static;
}
