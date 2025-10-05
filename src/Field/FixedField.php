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
    public function __construct(
        DataInterface $data,
        private int $startPosition,
        private int $length
    ) {
        parent::__construct($data);
    }

    public function getStartPosition(): int
    {
        return $this->startPosition;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getEndPosition(): int
    {
        return $this->startPosition + $this->length - 1;
    }

    public function extractFromLine(string $line): string
    {
        return \mb_substr($line, $this->startPosition, $this->length);
    }
}
