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
use Mazarini\BatchBundle\Exception\MethodNotImplementedException;
use Mazarini\BatchBundle\Exception\TypeMismatchException;

/**
 * Implements the DataInterface contract, acting only as a metadata container
 * (Type and Null Flag). All accessors are disabled to force external processing logic.
 *
 * @template T
 *
 * @internal this class is internal to the BatchBundle
 */
abstract class DataAbstract implements \Mazarini\BatchBundle\Contract\DataInterface
{
    /** @var TypeEnum The fixed, declared type of the parameter. */
    protected TypeEnum $type;

    /**
     * @param TypeEnum $type the fixed type this object represents
     */
    public function __construct(TypeEnum $type)
    {
        $this->type = $type;
    }

    // Abstract methods
    abstract public function isNull(): bool;

    abstract public function setNull(): static;

    abstract public function reset(): static;

    abstract public function setFormat(?string $format): static;

    abstract public function getAsIntegerOrNull(): ?int;

    abstract public function setAsIntegerOrNull(?int $value): static;

    abstract public function getAsDecimalOrNull(): ?float;

    abstract public function setAsDecimalOrNull(?float $value): static;

    abstract public function getAsDateTimeOrNull(): ?\DateTimeImmutable;

    abstract public function setAsDateTimeOrNull(?\DateTimeImmutable $value): static;

    abstract public function getAsBooleanOrNull(): ?bool;

    abstract public function setAsBooleanOrNull(?bool $value): static;

    abstract public function getAsStringOrNull(): ?string;

    abstract public function setAsStringOrNull(?string $value): static;

    // Public methods - interface order
    public function getRawValue(): string
    {
        $this->throwNotImplementedException(__FUNCTION__);
    }

    public function setRawValue(?string $rawValue): static
    {
        $this->throwNotImplementedException(__FUNCTION__);
    }

    public function getAsInteger(): int
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'integer');
    }

    public function setAsInteger(int $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'integer');
    }

    public function getAsDecimal(): float
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'decimal');
    }

    public function setAsDecimal(float $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'decimal');
    }

    public function getAsDateTime(): \DateTimeImmutable
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'datetime');
    }

    public function setAsDateTime(\DateTimeImmutable $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'datetime');
    }

    public function getAsBoolean(): bool
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'boolean');
    }

    public function setAsBoolean(bool $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'boolean');
    }

    public function getAsString(): string
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'string');
    }

    public function setAsString(string $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'string');
    }

    // -------------------------------------------------------------------------
    // EXCEPTION HANDLERS
    // -------------------------------------------------------------------------

    /**
     * Throws an exception for methods that are part of the interface but intentionally not implemented in this concrete class.
     */
    private function throwNotImplementedException(string $methodName): never
    {
        throw new MethodNotImplementedException(static::class, $methodName);
    }

    /**
     * Throws an exception indicating a type mismatch or a disabled typed accessor.
     */
    private function throwTypedMismatchDisabledException(string $methodName, string $expectedType): never
    {
        $currentType = $this->type->value;

        throw new TypeMismatchException($currentType, $methodName, $expectedType, static::class);
    }
}
