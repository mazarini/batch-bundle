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
 * @internal This class is internal to the BatchBundle
 */
class IntegerData extends DataAbstract
{
    private int $value;
    private int $filter;
    /**
     * @var array<string, mixed>
     */
    private array $options;

    /**
     * Initialize integer data with validation filter and optional range.
     *
     * @param int      $filter   PHP filter constant (default: FILTER_VALIDATE_INT)
     * @param int|null $minRange Minimum allowed value
     * @param int|null $maxRange Maximum allowed value
     */
    public function __construct(int $filter = \FILTER_VALIDATE_INT, ?int $minRange = null, ?int $maxRange = null)
    {
        parent::__construct(TypeEnum::INTEGER);
        $this->filter  = $filter;
        $this->options = [];

        if ($minRange !== null || $maxRange !== null) {
            $this->options['options'] = [];
            if ($minRange !== null) {
                $this->options['options']['min_range'] = $minRange;
            }
            if ($maxRange !== null) {
                $this->options['options']['max_range'] = $maxRange;
            }
        }
    }

    /**
     * Reset the internal value by unsetting it.
     */
    protected function resetValue(): static
    {
        unset($this->value);

        return $this;
    }

    /**
     * Get the raw integer value with optional formatting.
     *
     * @return string Formatted integer value
     */
    public function getRawValue(): string
    {
        return $this->formatScalarValue($this->value);
    }

    /**
     * Validate and convert string to integer.
     *
     * @param string $rawValue The raw string value to validate
     *
     * @throws \InvalidArgumentException If validation fails
     */
    protected function validateInteger(string $rawValue): int
    {
        $cleanValue = \mb_ltrim(\mb_trim($rawValue), '0');
        $cleanValue = $cleanValue !== '' ? $cleanValue : '0';

        $intValue = \filter_var($cleanValue, $this->filter, $this->options);

        if ($intValue === false) {
            throw new \InvalidArgumentException("Cannot convert '{$rawValue}' to integer");
        }

        return $intValue;
    }

    /**
     * Set raw value by delegating to setAsIntegerOrNull.
     *
     * @param string|null $rawValue The raw string value or null
     */
    public function setRawValue(?string $rawValue): static
    {
        $value = $rawValue === null ? null : $this->validateInteger($rawValue);

        return $this->setAsIntegerOrNull($value);
    }

    /**
     * Get the integer value, throws exception if null.
     *
     * @throws \InvalidArgumentException If value is null
     */
    public function getAsInteger(): int
    {
        if ($this->isNull()) {
            throw new \InvalidArgumentException('Value is null');
        }

        return $this->value;
    }

    /**
     * Get the integer value or null if not set.
     */
    public function getAsIntegerOrNull(): ?int
    {
        return $this->isNull() ? null : $this->value;
    }

    /**
     * Set integer value.
     *
     * @param int $value The integer value to set
     */
    public function setAsInteger(int $value): static
    {
        $this->value    = $value;
        $this->setNull(false);

        return $this;
    }

    /**
     * Set integer value or null.
     *
     * @param int|null $value The integer value to set or null
     */
    public function setAsIntegerOrNull(?int $value): static
    {
        if ($value === null) {
            return $this->setNull();
        }

        return $this->setAsInteger($value);
    }
}
