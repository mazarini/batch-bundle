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

use Mazarini\BatchBundle\Data\IntegerData;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class T30_IntegerDataTest extends TestCase
{
    private IntegerData $data;

    protected function setUp(): void
    {
        $this->data = new IntegerData();
    }

    public function testGetAsInteger(): void
    {
        $this->data->setAsInteger(42);
        Assert::assertSame(42, $this->data->getAsInteger());
    }

    public function testGetAsIntegerThrowsExceptionWhenNull(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value is null');
        $this->data->getAsInteger();
    }

    public function testGetAsIntegerOrNull(): void
    {
        Assert::assertNull($this->data->getAsIntegerOrNull());

        $this->data->setAsInteger(42);
        Assert::assertSame(42, $this->data->getAsIntegerOrNull());
    }

    public function testSetAsIntegerOrNull(): void
    {
        $this->data->setAsIntegerOrNull(null);
        Assert::assertTrue($this->data->isNull());

        $this->data->setAsIntegerOrNull(42);
        Assert::assertSame(42, $this->data->getAsInteger());
    }

    public function testSetRawValue(): void
    {
        $this->data->setRawValue('42');
        Assert::assertSame(42, $this->data->getAsInteger());

        $this->data->setRawValue(null);
        Assert::assertTrue($this->data->isNull());
    }

    public function testSetRawValueWithLeadingZeros(): void
    {
        $this->data->setRawValue('00042');
        Assert::assertSame(42, $this->data->getAsInteger());

        $this->data->setRawValue('000');
        Assert::assertSame(0, $this->data->getAsInteger());
    }

    public function testSetRawValueWithInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot validate 'abc' with filter in Mazarini\BatchBundle\Data\IntegerData.");
        $this->data->setRawValue('abc');
    }

    public function testGetRawValue(): void
    {
        $this->data->setAsInteger(42);
        Assert::assertSame('42', $this->data->getRawValue());
    }

    public function testGetRawValueWithFormat(): void
    {
        $this->data->setAsInteger(42);
        $this->data->setFormat('%05d');
        Assert::assertSame('00042', $this->data->getRawValue());
    }

    public function testConstructorWithRange(): void
    {
        $data = new IntegerData(\FILTER_VALIDATE_INT, 1, 100);

        $data->setRawValue('50');
        Assert::assertSame(50, $data->getAsInteger());

        $this->expectException(\InvalidArgumentException::class);
        $data->setRawValue('200');
    }
}
