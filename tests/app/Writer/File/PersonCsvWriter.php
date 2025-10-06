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
use Mazarini\BatchBundle\Data\BooleanData;
use Mazarini\BatchBundle\Data\DecimalData;
use Mazarini\BatchBundle\Data\IntegerData;
use Mazarini\BatchBundle\Data\StringData;
use Mazarini\BatchBundle\Writer\File\CsvWriter;

/**
 * CSV Writer for person data.
 * Defines the structure for exporting person records to CSV format.
 */
class PersonCsvWriter extends CsvWriter
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
        $dataCollection->add('is_active', new BooleanData());
        $dataCollection->add('salary', new DecimalData());

        return $dataCollection;
    }
}
