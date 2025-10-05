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

namespace Mazarini\BatchBundle\Field;

use Mazarini\BatchBundle\Contract\DataInterface;

/**
 * @internal This class is internal to the BatchBundle
 */
class CsvField extends Field
{
    public function __construct(
        DataInterface $data,
        private int $position
    ) {
        parent::__construct($data);
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Populates the field data from a CSV row array.
     *
     * @param array<string> $csvRow The CSV row data
     */
    public function populateFromCsvRow(array $csvRow): static
    {
        $value = $csvRow[$this->position] ?? null;
        $this->getData()->setRawValue($value);

        return $this;
    }
}
