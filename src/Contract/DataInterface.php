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

namespace Mazarini\BatchBundle\Contract;

use DateTimeImmutable;

/**
 * Defines the contract for an object representing a single Job parameter value.
 * The object stores a raw value (always string or null) and provides methods
 * to retrieve and set it with specific type casting.
 *
 * @internal This interface is internal to the BatchBundle
 */
interface DataInterface
{
    /**
     * Retrieves the raw, converted value to string.
     * This value is typically a string for files.
     */
    public function getRawValue(): string;

    /**
     * Sets the raw, value as string to be converted.
     */
    public function setRawValue(?string $rawValue): static;

    /**
     * Reset the value.
     */
    public function reset(): static;

    // -------------------------------------------------------------------------
    // NULL management methods
    // -------------------------------------------------------------------------

    /**
     * Checks if the value of the parameter is considered null (null or empty string).
     */
    public function isNull(): bool;

    /**
     * Sets the value to null.
     */
    public function setNull(bool $flagNull): static;

    // -------------------------------------------------------------------------
    // INTEGER methods
    // -------------------------------------------------------------------------

    /**
     * Gets the value converted to an integer.
     *
     * @throws \InvalidArgumentException if the value cannot be converted or is null/empty
     */
    public function getAsInteger(): int;

    /**
     * Gets the value converted to an integer, or null if the raw value is considered null.
     */
    public function getAsIntegerOrNull(): ?int;

    /**
     * Sets the value as an integer.
     */
    public function setAsInteger(int $value): static;

    /**
     * Sets the value, accepting null.
     */
    public function setAsIntegerOrNull(?int $value): static;

    // -------------------------------------------------------------------------
    // DECIMAL methods (float)
    // -------------------------------------------------------------------------

    /**
     * Gets the value converted to a float (decimal).
     *
     * @throws \InvalidArgumentException if the value cannot be converted or is null/empty
     */
    public function getAsDecimal(): float;

    /**
     * Gets the value converted to a float, or null if the raw value is considered null.
     */
    public function getAsDecimalOrNull(): ?float;

    /**
     * Sets the value as a float (decimal).
     */
    public function setAsDecimal(float $value): static;

    /**
     * Sets the value, accepting null.
     */
    public function setAsDecimalOrNull(?float $value): static;

    // -------------------------------------------------------------------------
    // DATETIME methods (DateTimeImmutable)
    // -------------------------------------------------------------------------

    /**
     * Gets the value converted to an immutable DateTime object.
     *
     * @throws \InvalidArgumentException if the value cannot be converted or is null/empty
     */
    public function getAsDateTime(): \DateTimeImmutable;

    /**
     * Gets the value converted to an immutable DateTime object, or null if the raw value is considered null.
     */
    public function getAsDateTimeOrNull(): ?\DateTimeImmutable;

    /**
     * Sets the value as an immutable DateTime object.
     */
    public function setAsDateTime(\DateTimeImmutable $value): static;

    /**
     * Sets the value, accepting null.
     */
    public function setAsDateTimeOrNull(?\DateTimeImmutable $value): static;

    // -------------------------------------------------------------------------
    // BOOLEAN methods
    // -------------------------------------------------------------------------

    /**
     * Gets the value converted to a boolean.
     *
     * @throws \InvalidArgumentException if the value cannot be converted or is null/empty
     */
    public function getAsBoolean(): bool;

    /**
     * Gets the value converted to a boolean, or null if the raw value is considered null.
     */
    public function getAsBooleanOrNull(): ?bool;

    /**
     * Sets the value as a boolean.
     */
    public function setAsBoolean(bool $value): static;

    /**
     * Sets the value, accepting null.
     */
    public function setAsBooleanOrNull(?bool $value): static;

    // -------------------------------------------------------------------------
    // STRING methods
    // -------------------------------------------------------------------------

    /**
     * Gets the value converted to a string.
     *
     * @throws \InvalidArgumentException if the value is null/empty
     */
    public function getAsString(): string;

    /**
     * Gets the value converted to a string, or null if the raw value is considered null.
     */
    public function getAsStringOrNull(): ?string;

    /**
     * Sets the value as a string.
     */
    public function setAsString(string $value): static;

    /**
     * Sets the value, accepting null.
     */
    public function setAsStringOrNull(?string $value): static;
}
