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
 * @internal This class is internal to the BatchBundle
 */
class StringData extends DataAbstract
{
    private string $value;

    /**
     * Initialize string data with STRING type.
     */
    public function __construct()
    {
        parent::__construct(TypeEnum::STRING);
    }

    /**
     * Get the raw string value with optional formatting.
     *
     * @return string Empty string if null, formatted value otherwise
     */
    public function getRawValue(): string
    {
        return $this->formatScalarValue($this->isNull() ? '' : $this->value);
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
     * Set raw value by delegating to setAsStringOrNull.
     *
     * @param string|null $rawValue The raw string value or null
     */
    public function setRawValue(?string $rawValue): static
    {
        return $this->setAsStringOrNull($rawValue);
    }

    /**
     * Get the string value, throws exception if null.
     *
     * @throws \InvalidArgumentException If value is null
     */
    public function getAsString(): string
    {
        if ($this->isNull()) {
            throw new \InvalidArgumentException('Value is null');
        }

        return $this->value;
    }

    /**
     * Get the string value or null if not set.
     */
    public function getAsStringOrNull(): ?string
    {
        return $this->isNull() ? null : $this->value;
    }

    /**
     * Set string value with automatic trimming.
     *
     * @param string $value The string value to set (will be trimmed)
     */
    public function setAsString(string $value): static
    {
        $this->value = \mb_trim($value);
        $this->setNull(false);

        return $this;
    }

    /**
     * Set string value or null with automatic trimming.
     *
     * @param string|null $value The string value to set or null
     */
    public function setAsStringOrNull(?string $value): static
    {
        if ($value === null) {
            return $this->setNull();
        }

        return $this->setAsString($value);
    }
}
