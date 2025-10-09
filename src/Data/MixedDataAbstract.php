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
 * @template T
 *
 * @extends DataAbstract<T>
 *
 * @internal This class is internal to the BatchBundle
 */
abstract class MixedDataAbstract extends DataAbstract
{
    /** @var T|null */
    protected mixed $value = null;

    /**
     * @var string|null Format string
     *                  - for sprintf() function (e.g., '%05d', '%.2f', '%s')
     *                  - for DateTime::format
     */
    protected ?string $format = null;

    public function __construct(TypeEnum $type)
    {
        parent::__construct($type);
        $this->reset();
    }

    /**
     * Checks if the value of the parameter is considered null (null or empty string).
     */
    public function isNull(): bool
    {
        return null === $this->value;
    }

    /**
     * Sets the value to null.
     */
    public function setNull(): static
    {
        $this->value = null;

        return $this;
    }

    /**
     * Reset the value.
     */
    public function reset(): static
    {
        return $this->setNull();
    }

    /**
     * Sets the format string for getRawValue() output formatting.
     *
     * @param string|null $format Format string for sprintf() (e.g., '%05d', '%.2f', '%s') or null to disable formatting
     */
    public function setFormat(?string $format): static
    {
        $this->format = $format;

        return $this;
    }

    // Implemented methods
    /**
     * Gets the value converted to an integer, or null if the raw value is considered null.
     */
    public function getAsIntegerOrNull(): ?int
    {
        return $this->isNull() ? null : $this->getAsInteger();
    }

    /**
     * Sets the value, accepting null.
     */
    public function setAsIntegerOrNull(?int $value): static
    {
        if ($value === null) {
            return $this->setNull();
        }

        return $this->setAsInteger($value);
    }

    /**
     * Gets the value converted to a float, or null if the raw value is considered null.
     */
    public function getAsDecimalOrNull(): ?float
    {
        return $this->isNull() ? null : $this->getAsDecimal();
    }

    /**
     * Sets the value, accepting null.
     */
    public function setAsDecimalOrNull(?float $value): static
    {
        if ($value === null) {
            return $this->setNull();
        }

        return $this->setAsDecimal($value);
    }

    /**
     * Gets the value converted to an immutable DateTime object, or null if the raw value is considered null.
     */
    public function getAsDateTimeOrNull(): ?\DateTimeImmutable
    {
        return $this->isNull() ? null : $this->getAsDateTime();
    }

    /**
     * Sets the value, accepting null.
     */
    public function setAsDateTimeOrNull(?\DateTimeImmutable $value): static
    {
        if ($value === null) {
            return $this->setNull();
        }

        return $this->setAsDateTime($value);
    }

    /**
     * Gets the value converted to a boolean, or null if the raw value is considered null.
     */
    public function getAsBooleanOrNull(): ?bool
    {
        return $this->isNull() ? null : $this->getAsBoolean();
    }

    /**
     * Sets the value, accepting null.
     */
    public function setAsBooleanOrNull(?bool $value): static
    {
        if ($value === null) {
            return $this->setNull();
        }

        return $this->setAsBoolean($value);
    }

    /**
     * Gets the value converted to a string, or null if the raw value is considered null.
     */
    public function getAsStringOrNull(): ?string
    {
        return $this->isNull() ? null : $this->getAsString();
    }

    /**
     * Sets the value, accepting null.
     */
    public function setAsStringOrNull(?string $value): static
    {
        if ($value === null) {
            return $this->setNull();
        }

        return $this->setAsString($value);
    }
}
