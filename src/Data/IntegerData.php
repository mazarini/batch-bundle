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
 * Integer data type implementation with validation and range checking.
 * Handles mainframe-style leading zeros removal.
 *
 * @extends ScalarDataAbstract<int>
 *
 * @internal This class is internal to the BatchBundle
 */
class IntegerData extends ScalarDataAbstract
{
    /**
     * Initialize integer data with optional range validation.
     *
     * @param int|null $minRange Minimum allowed value
     * @param int|null $maxRange Maximum allowed value
     */
    public function __construct(int $filter = \FILTER_VALIDATE_INT, ?int $minRange = null, ?int $maxRange = null)
    {
        $options = [];
        if ($minRange !== null || $maxRange !== null) {
            $options['options'] = [];
            if ($minRange !== null) {
                $options['options']['min_range'] = $minRange;
            }
            if ($maxRange !== null) {
                $options['options']['max_range'] = $maxRange;
            }
        }

        parent::__construct(TypeEnum::INTEGER, $filter, $options);
    }

    /**
     * Override clean method to handle leading zeros removal for mainframe compatibility.
     */
    protected function clean(string $rawValue): string
    {
        $trimmed             = \mb_trim($rawValue);
        $withoutLeadingZeros = \mb_ltrim($trimmed, '0');

        return $withoutLeadingZeros !== '' ? $withoutLeadingZeros : '0';
    }

    /**
     * Gets the integer value, throws exception if null.
     */
    public function getAsInteger(): int
    {
        if ($this->isNull()) {
            throw new \InvalidArgumentException('Value is null');
        }

        return $this->value;
    }

    /**
     * Sets the integer value.
     */
    public function setAsInteger(int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
