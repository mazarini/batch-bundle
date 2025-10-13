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

use Mazarini\BatchBundle\Contract\Resetable;

/**
 * A typed collection of items accessible via string or integer keys.
 *
 * @template TKey of array-key
 * @template TValue of Resetable
 *
 * @implements \IteratorAggregate<TKey, TValue>
 * @implements \ArrayAccess<TKey, TValue>
 */
class ObjectArray implements \IteratorAggregate, \ArrayAccess, \Countable, Resetable
{
    /**
     * The items stored in the collection.
     *
     * @var array<TKey, TValue>
     */
    protected array $items = [];

    /**
     * Initializes the collection with an array of objects.
     *
     * @param array<TKey, TValue> $items array of objects to store in the collection
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Returns an iterator to traverse the collection.
     *
     * @return \Traversable<TKey, TValue> iterator for the collection
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Checks whether a key exists in the collection.
     *
     * @param TKey $offset the key to check
     *
     * @return bool true if the key exists, false otherwise
     */
    public function offsetExists(mixed $offset): bool
    {
        return \array_key_exists($offset, $this->items);
    }

    /**
     * Retrieves an item from the collection by key.
     *
     * @param TKey $offset the key of the item
     *
     * @return TValue|null the object associated with the key, or null if not found
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * Adds or replaces an item in the collection.
     *
     * @param TKey|null $offset the key of the item
     * @param TValue    $value  the object to store
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (null === $offset) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Removes an item from the collection by key.
     *
     * @param TKey $offset the key of the item to remove
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

    /**
     * Resets each item in the collection.
     */
    public function reset(): static
    {
        foreach ($this->items as $item) {
            $item->reset();
        }

        return $this;
    }
}
