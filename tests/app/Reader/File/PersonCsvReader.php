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

namespace App\Reader\File;

use Mazarini\BatchBundle\Reader\File\CsvReader;

/**
 * CSV Reader for person data with predefined structure.
 * Expected CSV format: id,first_name,last_name,email,age,birth_date,is_active.
 */
class PersonCsvReader extends CsvReader
{
    /**
     * Defines the CSV structure for person data.
     *
     * @return array<string, int> Field names mapped to their CSV column positions
     */
    public function getStructure(): array
    {
        return [
            'id'         => 0,
            'first_name' => 1,
            'last_name'  => 2,
            'email'      => 3,
            'age'        => 4,
            'birth_date' => 5,
            'is_active'  => 6,
            'salary'     => 7,
        ];
    }
}
