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

/**
 * A typed collection of ItemInterface accessible via string keys.
 *
 * @template T of Object
 *
 * @implements \IteratorAggregate<string, T>
 * @implements \ArrayAccess<string, T>
 */
class ObjectCollection implements \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * The items stored in the collection, indexed by string keys.
     *
     * @var array<string, T>
     */
    protected array $items = [];

    /**
     * Initializes the collection with an array of objects.
     *
     * @param array<string, T> $items array of objects to store in the collection
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Returns an iterator to traverse the collection.
     *
     * @return \Traversable<string, T> iterator for the collection
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Checks whether a key exists in the collection.
     *
     * @param mixed $offset the key to check
     *
     * @return bool true if the key exists and is a string, false otherwise
     */
    public function offsetExists(mixed $offset): bool
    {
        return \is_string($offset) && \array_key_exists($offset, $this->items);
    }

    /**
     * Retrieves an item from the collection by key.
     *
     * @param string $offset the key of the item
     *
     * @return T|null the object associated with the key, or null if not found
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * Adds or replaces an item in the collection.
     *
     * @param mixed $offset the key of the item (must be a string, null not allowed)
     * @param T     $value  the object to store
     *
     * @throws \InvalidArgumentException if the key is not a string
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            throw new \InvalidArgumentException('String key is required for ObjectCollection.');
        }

        if (! \is_string($offset)) {
            throw new \InvalidArgumentException('Key must be a string.');
        }

        $this->items[$offset] = $value;
    }

    /**
     * Removes an item from the collection by key.
     *
     * @param string $offset the key of the item to remove
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * Returns the number of items in the collection.
     *
     * @return int<0, max> number of stored items
     */
    public function count(): int
    {
        return \count($this->items);
    }
}
