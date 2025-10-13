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

namespace Mazarini\BatchBundle\Collection;

use Mazarini\BatchBundle\Contract\DataCollectionInterface;
use Mazarini\BatchBundle\Contract\DataInterface;

/**
 * @extends ObjectArray<string, DataInterface>
 */
class DataCollection extends ObjectArray implements DataCollectionInterface
{
    /**
     * @param array<string, DataInterface> $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }

    public function add(string $key, DataInterface $data): static
    {
        $this->items[$key] = $data;

        return $this;
    }

    public function get(string $key): ?DataInterface
    {
        return $this->items[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->items[$key]);
    }

    public function remove(string $key): static
    {
        unset($this->items[$key]);

        return $this;
    }

    public function getKeys(): array
    {
        return \array_keys($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function clear(): static
    {
        $this->items = [];

        return $this;
    }

    /**
     * Resets each data item in the collection.
     */
    public function reset(): static
    {
        foreach ($this->items as $data) {
            $data->reset();
        }

        return $this;
    }

    /**
     * Creates a Record containing a subset of data types from this collection.
     * Creates new instances of the same DataInterface types for independent population by readers.
     *
     * @param array<string> $keys Keys to include in the record
     */
    public function createRecord(array $keys): Record
    {
        $recordItems = [];
        foreach ($keys as $key) {
            if (isset($this->items[$key])) {
                $recordItems[$key] = clone $this->items[$key];
            }
        }

        return new Record($recordItems);
    }
}
