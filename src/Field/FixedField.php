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

class FixedField extends Field
{
    private ?int $startPosition = null;

    public function __construct(
        private int $length,
        string $name,
        ?DataInterface $data = null
    ) {
        parent::__construct($name, $data);
    }

    public function getStartPosition(): int
    {
        if (null === $this->startPosition) {
            throw new \RuntimeException('You cannot call getStartPosition() when the startPosition is null. Configure object FixedRecord first.');
        }

        return $this->startPosition;
    }

    /**
     * setStartPosition.
     *
     * @param int $startPosition start position of this field
     *
     * @return int start position of next field
     */
    public function setStartPosition(int $startPosition): int
    {
        $this->startPosition = $startPosition;

        return $this->startPosition + $this->length;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Checks if the field contains a Data object and startPosition is set.
     *
     * @return bool returns true if no Data object is set, false otherwise
     */
    public function isReady(): bool
    {
        return parent::isReady() && (null !== $this->startPosition);
    }
}
