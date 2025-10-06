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

namespace App\Tests\Data;

use Mazarini\BatchBundle\Data\DataAbstract;
use Mazarini\BatchBundle\Enum\TypeEnum;
use PHPUnit\Framework\TestCase;

class DataExceptionAbstractTest extends TestCase
{
    private DataAbstract $dataString;
    private DataAbstract $dataInteger;

    protected function setUp(): void
    {
        $this->dataString = new class(TypeEnum::STRING) extends DataAbstract {
            protected function resetValue(): static
            {
                return $this;
            }
        };

        $this->dataInteger = new class(TypeEnum::INTEGER) extends DataAbstract {
            protected function resetValue(): static
            {
                return $this;
            }
        };
    }

    public function testGetRawValueThrowsException(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('::getRawValue is not implemented in this object. Use the external Processor for raw value manipulation.');
        $this->dataString->getRawValue();
    }

    public function testSetRawValueThrowsException(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('::setRawValue is not implemented in this object. Use the external Processor for raw value manipulation.');
        $this->dataString->setRawValue('test');
    }

    public function testGetAsIntegerThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (getAsInteger) expects "integer"');
        $this->dataString->getAsInteger();
    }

    public function testSetAsIntegerThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (setAsInteger) expects "integer"');
        $this->dataString->setAsInteger(42);
    }

    public function testGetAsDecimalThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (getAsDecimal) expects "decimal"');
        $this->dataString->getAsDecimal();
    }

    public function testSetAsDecimalThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (setAsDecimal) expects "decimal"');
        $this->dataString->setAsDecimal(3.14);
    }

    public function testGetAsBooleanThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (getAsBoolean) expects "boolean"');
        $this->dataString->getAsBoolean();
    }

    public function testSetAsBooleanThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (setAsBoolean) expects "boolean"');
        $this->dataString->setAsBoolean(true);
    }

    public function testGetAsDateTimeThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (getAsDateTime) expects "datetime"');
        $this->dataString->getAsDateTime();
    }

    public function testSetAsDateTimeThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (setAsDateTime) expects "datetime"');
        $this->dataString->setAsDateTime(new \DateTimeImmutable());
    }

    public function testGetAsStringThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "integer", but the method called (getAsString) expects "string"');
        $this->dataInteger->getAsString();
    }

    public function testSetAsStringThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "integer", but the method called (setAsString) expects "string"');
        $this->dataInteger->setAsString('test');
    }

    public function testGetAsIntegerOrNullThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (getAsIntegerOrNull) expects "integer"');
        $this->dataString->getAsIntegerOrNull();
    }

    public function testSetAsIntegerOrNullThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (setAsIntegerOrNull) expects "integer"');
        $this->dataString->setAsIntegerOrNull(42);
    }

    public function testGetAsDecimalOrNullThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (getAsDecimalOrNull) expects "decimal"');
        $this->dataString->getAsDecimalOrNull();
    }

    public function testSetAsDecimalOrNullThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (setAsDecimalOrNull) expects "decimal"');
        $this->dataString->setAsDecimalOrNull(3.14);
    }

    public function testGetAsBooleanOrNullThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (getAsBooleanOrNull) expects "boolean"');
        $this->dataString->getAsBooleanOrNull();
    }

    public function testSetAsBooleanOrNullThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (setAsBooleanOrNull) expects "boolean"');
        $this->dataString->setAsBooleanOrNull(true);
    }

    public function testGetAsDateTimeOrNullThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (getAsDateTimeOrNull) expects "datetime"');
        $this->dataString->getAsDateTimeOrNull();
    }

    public function testSetAsDateTimeOrNullThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "string", but the method called (setAsDateTimeOrNull) expects "datetime"');
        $this->dataString->setAsDateTimeOrNull(new \DateTimeImmutable());
    }

    public function testGetAsStringOrNullThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "integer", but the method called (getAsStringOrNull) expects "string"');
        $this->dataInteger->getAsStringOrNull();
    }

    public function testSetAsStringOrNullThrowsTypeMismatch(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Type mismatch: The parameter type is "integer", but the method called (setAsStringOrNull) expects "string"');
        $this->dataInteger->setAsStringOrNull('test');
    }
}
