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

use Mazarini\BatchBundle\Reader\File\FixedReader;

/**
 * Fixed-width reader for person data with predefined structure.
 * Expected format: ID(5) + FirstName(20) + LastName(20) + Email(30) + Age(3) + Salary(10).
 */
class PersonFixedReader extends FixedReader
{
    /**
     * Defines the fixed-width structure for person data.
     *
     * @return array<string, array{start: int, length: int}> Field names mapped to start position and length
     */
    public function getStructure(): array
    {
        return [
            'id'         => ['start' => 0,  'length' => 5],
            'first_name' => ['start' => 5,  'length' => 20],
            'last_name'  => ['start' => 25, 'length' => 20],
            'email'      => ['start' => 45, 'length' => 30],
            'age'        => ['start' => 75, 'length' => 3],
            'salary'     => ['start' => 78, 'length' => 10],
        ];
    }
}
