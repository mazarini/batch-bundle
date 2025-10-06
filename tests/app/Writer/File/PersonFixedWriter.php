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

namespace App\Writer\File;

use Mazarini\BatchBundle\Collection\DataCollection;
use Mazarini\BatchBundle\Data\DecimalData;
use Mazarini\BatchBundle\Data\IntegerData;
use Mazarini\BatchBundle\Data\StringData;
use Mazarini\BatchBundle\Writer\File\FixedWriter;

/**
 * Fixed-width Writer for person data.
 * Generates fixed-width format compatible with COBOL/mainframe systems.
 */
class PersonFixedWriter extends FixedWriter
{
    /**
     * Create the DataCollection with person data structure.
     */
    protected function createDataCollection(): DataCollection
    {
        $dataCollection = new DataCollection();
        $dataCollection->add('id', new IntegerData());
        $dataCollection->add('first_name', new StringData());
        $dataCollection->add('last_name', new StringData());
        $dataCollection->add('email', new StringData());
        $dataCollection->add('age', new IntegerData());
        $dataCollection->add('salary', new DecimalData());

        return $dataCollection;
    }

    /**
     * Define the fixed-width structure with sprintf formats.
     */
    protected function getStructure(): array
    {
        return [
            'id'         => ['format' => '%05d'],           // 5 chars, zero-padded
            'first_name' => ['format' => '%-20s'],  // 20 chars, left-aligned
            'last_name'  => ['format' => '%-20s'],   // 20 chars, left-aligned
            'email'      => ['format' => '%-30s'],       // 30 chars, left-aligned
            'age'        => ['format' => '%03d'],          // 3 chars, zero-padded
            'salary'     => ['format' => '%010.2f|no_dot'], // 10 chars, remove decimal point
        ];
    }
}
