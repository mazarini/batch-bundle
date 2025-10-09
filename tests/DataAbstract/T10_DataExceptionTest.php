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

namespace App\Tests\DataAbstract;

use App\Tests\DataAbstract\Stub\DataAbstractStub;
use Mazarini\BatchBundle\Data\DataAbstract;
use Mazarini\BatchBundle\Enum\TypeEnum;
use Mazarini\BatchBundle\Exception\MethodNotImplementedException;
use Mazarini\BatchBundle\Exception\TypeMismatchException;
use PHPUnit\Framework\TestCase;

class T10_DataExceptionTest extends TestCase
{
    /** @var DataAbstract<string> */
    private DataAbstract $data;
    private string $notImplemented = 'Function %s::%s is not implemented in this object. Use the external Processor for raw value manipulation.';
    private string $typeMismatch   = 'Type mismatch: The parameter type is "%s", but the method called (%s) expects "%s"';

    protected function setUp(): void
    {
        $this->data  = new DataAbstractStub(TypeEnum::STRING);
    }

    public function testGetRawValueThrowsException(): void
    {
        $this->expectNotImplementedException('getRawValue');
        $this->data->getRawValue();
    }

    public function testSetRawValueThrowsException(): void
    {
        $this->expectNotImplementedException('setRawValue');
        $this->data->setRawValue('test');
    }

    public function testGetAsIntegerThrowsTypeMismatch(): void
    {
        $this->expectTypeMismatchException('getAsInteger');
        $this->data->getAsInteger();
    }

    public function testSetAsIntegerThrowsTypeMismatch(): void
    {
        $this->expectTypeMismatchException('setAsInteger');
        $this->data->setAsInteger(42);
    }

    public function testGetAsDecimalThrowsTypeMismatch(): void
    {
        $this->expectTypeMismatchException('getAsDecimal');
        $this->data->getAsDecimal();
    }

    public function testSetAsDecimalThrowsTypeMismatch(): void
    {
        $this->expectTypeMismatchException('setAsDecimal');
        $this->data->setAsDecimal(3.14);
    }

    public function testGetAsBooleanThrowsTypeMismatch(): void
    {
        $this->expectTypeMismatchException('getAsBoolean');
        $this->data->getAsBoolean();
    }

    public function testSetAsBooleanThrowsTypeMismatch(): void
    {
        $this->expectTypeMismatchException('setAsBoolean');
        $this->data->setAsBoolean(true);
    }

    public function testGetAsDateTimeThrowsTypeMismatch(): void
    {
        $this->expectTypeMismatchException('getAsDateTime');
        $this->data->getAsDateTime();
    }

    public function testSetAsDateTimeThrowsTypeMismatch(): void
    {
        $this->expectTypeMismatchException('setAsDateTime');
        $this->data->setAsDateTime(new \DateTimeImmutable());
    }

    public function testGetAsStringThrowsTypeMismatch(): void
    {
        $this->expectTypeMismatchException('getAsString');
        $this->data->getAsString();
    }

    public function testSetAsStringThrowsTypeMismatch(): void
    {
        $this->expectTypeMismatchException('setAsString');
        $this->data->setAsString('test');
    }

    private function expectNotImplementedException(string $method): void
    {
        $expected = \sprintf($this->notImplemented, DataAbstractStub::class, $method);
        $this->expectException(MethodNotImplementedException::class);
        $this->expectExceptionMessage($expected);
    }

    private function expectTypeMismatchException(string $method, string $type = 'string'): void
    {
        $expectedType = \mb_strtolower(\mb_substr($method, 5));
        $expected     = \sprintf($this->typeMismatch, $type, $method, $expectedType);
        $this->expectException(TypeMismatchException::class);
        $this->expectExceptionMessage($expected);
    }
}
