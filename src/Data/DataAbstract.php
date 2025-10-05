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

use Mazarini\BatchBundle\Contract\DataInterface;
use Mazarini\BatchBundle\Enum\TypeEnum;

/**
 * Implements the DataInterface contract, acting only as a metadata container
 * (Type and Null Flag). All accessors are disabled to force external processing logic.
 *
 * @internal this class is internal to the BatchBundle
 */
abstract class DataAbstract implements DataInterface
{
    /** @var TypeEnum The fixed, declared type of the parameter. */
    private TypeEnum $type;

    /** @var bool TRUE if the value is logically NULL (the DB2 null indicator). */
    private bool $nullFlag = true;

    protected ?string $format = null;

    /**
     * @param TypeEnum $type the fixed type this object represents
     */
    public function __construct(TypeEnum $type)
    {
        $this->type = $type;
        $this->reset();
    }

    // -------------------------------------------------------------------------
    // NULL MANAGEMENT & METADATA (Functional)
    // -------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     */
    public function isNull(): bool
    {
        return $this->nullFlag;
    }

    /**
     * {@inheritDoc}
     * Sets the value to NULL by updating ONLY the indicator.
     */
    public function setNull(bool $flagNull = true): static
    {
        $this->nullFlag = $flagNull;
        if ($flagNull) {
            $this->resetValue();
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function reset(): static
    {
        return $this->setNull();
    }

    abstract protected function resetValue(): static;

    // -------------------------------------------------------------------------
    // EXCEPTION HANDLERS
    // -------------------------------------------------------------------------

    /**
     * Throws an exception for methods that are part of the interface but intentionally not implemented in this concrete class.
     */
    private function throwNotImplementedException(string $methodName): never
    {
        throw new \BadMethodCallException(\sprintf('Function %s::%s is not implemented in this object. Use the external Processor for raw value manipulation.', static::class, $methodName));
    }

    /**
     * Throws an exception indicating a type mismatch or a disabled typed accessor.
     */
    private function throwTypedMismatchDisabledException(string $methodName, string $expectedType): never
    {
        $currentType = $this->type->value;

        throw new \BadMethodCallException(\sprintf('Type mismatch: The parameter type is "%s", but the method called (%s) expects "%s". The function is disabled in %s.', $currentType, $methodName, $expectedType, static::class));
    }

    // -------------------------------------------------------------------------
    // RAW ACCESSORS (FORCED FAILURE: NOT IMPLEMENTED)
    // -------------------------------------------------------------------------

    public function getRawValue(): string
    {
        $this->throwNotImplementedException(__FUNCTION__);
    }

    public function setRawValue(?string $rawValue): static
    {
        $this->throwNotImplementedException(__FUNCTION__);
    }

    // -------------------------------------------------------------------------
    // TYPED ACCESSORS (FORCED FAILURE: TYPE MISMATCH)
    // -------------------------------------------------------------------------

    public function getAsInteger(): int
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'integer');
    }

    public function getAsIntegerOrNull(): ?int
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'integer');
    }

    public function setAsInteger(int $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'integer');
    }

    public function setAsIntegerOrNull(?int $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'integer');
    }

    public function getAsDecimal(): float
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'decimal');
    }

    public function getAsDecimalOrNull(): ?float
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'decimal');
    }

    public function setAsDecimal(float $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'decimal');
    }

    public function setAsDecimalOrNull(?float $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'decimal');
    }

    public function getAsDateTime(): \DateTimeImmutable
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'datetime');
    }

    public function getAsDateTimeOrNull(): ?\DateTimeImmutable
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'datetime');
    }

    public function setAsDateTime(\DateTimeImmutable $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'datetime');
    }

    public function setAsDateTimeOrNull(?\DateTimeImmutable $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'datetime');
    }

    public function getAsBoolean(): bool
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'boolean');
    }

    public function getAsBooleanOrNull(): ?bool
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'boolean');
    }

    public function setAsBoolean(bool $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'boolean');
    }

    public function setAsBooleanOrNull(?bool $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'boolean');
    }

    public function getAsString(): string
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'string');
    }

    public function getAsStringOrNull(): ?string
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'string');
    }

    public function setAsString(string $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'string');
    }

    public function setAsStringOrNull(?string $value): static
    {
        $this->throwTypedMismatchDisabledException(__FUNCTION__, 'string');
    }

    public function setFormat(?string $format): static
    {
        $this->format = $format;

        return $this;
    }

    /**
     * formatScalarValue.
     *
     * @param scalar $value
     */
    protected function formatScalarValue(mixed $value): string
    {
        return $this->format === null
            ? (string) $value
            : \sprintf($this->format, $value);
    }
}
