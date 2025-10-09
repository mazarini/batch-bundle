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

namespace Mazarini\BatchBundle\Data;

use Mazarini\BatchBundle\Enum\TypeEnum;

/**
 * String data type implementation with automatic trimming.
 *
 * @extends ScalarDataAbstract<string>
 *
 * @internal This class is internal to the BatchBundle
 */
class StringData extends ScalarDataAbstract
{
    /**
     * Initialize string data.
     */
    public function __construct()
    {
        parent::__construct(TypeEnum::STRING, \FILTER_UNSAFE_RAW, []);
    }

    /**
     * Gets the string value, throws exception if null.
     */
    public function getAsString(): string
    {
        if ($this->isNull()) {
            throw new \InvalidArgumentException('Value is null');
        }

        return $this->value;
    }

    /**
     * Sets the string value with automatic trimming.
     */
    public function setAsString(string $value): static
    {
        return $this->validateWithFilter($value);
    }
}
