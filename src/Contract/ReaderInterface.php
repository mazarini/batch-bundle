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

use Mazarini\BatchBundle\Collection\DataCollection;
use Mazarini\BatchBundle\Collection\Record;

/**
 * Defines the contract for readers that provide records from various sources.
 * Readers can read from CSV files, databases, APIs, etc. and yield Record objects.
 *
 * @internal This interface is internal to the BatchBundle
 */
interface ReaderInterface
{
    /**
     * Configures the reader with DataCollection and field names to populate.
     *
     * @param DataCollection $dataCollection The data structure definition
     * @param array<string>  $fieldNames     List of field names to populate
     */
    public function configure(DataCollection $dataCollection, array $fieldNames): static;

    /**
     * Opens/initializes the reader (e.g., open file, connect to database).
     */
    public function open(): static;

    /**
     * Reads and returns the next record from the source.
     * Returns null when no more records are available.
     */
    public function read(): bool;

    /**
     * Closes/finalizes the reader (e.g., close file, disconnect from database).
     */
    public function close(): static;

    /**
     * Reads records from the source and yields them one by one.
     * This allows for memory-efficient processing of large datasets.
     * Handles open/close lifecycle automatically.
     *
     * @return \Generator<Record> Generator yielding Record objects
     */
    public function getRecords(): \Generator;
}
