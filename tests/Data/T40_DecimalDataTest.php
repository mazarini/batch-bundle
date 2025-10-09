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

use Mazarini\BatchBundle\Data\DecimalData;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class T40_DecimalDataTest extends TestCase
{
    private DecimalData $data;

    protected function setUp(): void
    {
        $this->data = new DecimalData();
    }

    public function testGetAsDecimal(): void
    {
        $this->data->setAsDecimal(3.14);
        Assert::assertSame(3.14, $this->data->getAsDecimal());
    }

    public function testGetAsDecimalThrowsExceptionWhenNull(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value is null');
        $this->data->getAsDecimal();
    }

    public function testGetAsDecimalOrNull(): void
    {
        Assert::assertNull($this->data->getAsDecimalOrNull());

        $this->data->setAsDecimal(3.14);
        Assert::assertSame(3.14, $this->data->getAsDecimalOrNull());
    }

    public function testSetAsDecimalOrNull(): void
    {
        $this->data->setAsDecimalOrNull(null);
        Assert::assertTrue($this->data->isNull());

        $this->data->setAsDecimalOrNull(3.14);
        Assert::assertSame(3.14, $this->data->getAsDecimal());
    }

    public function testSetRawValue(): void
    {
        $this->data->setRawValue('3.14');
        Assert::assertSame(3.14, $this->data->getAsDecimal());

        $this->data->setRawValue(null);
        Assert::assertTrue($this->data->isNull());
    }

    public function testSetRawValueWithThousandSeparators(): void
    {
        $this->data->setRawValue('1,234.56');
        Assert::assertSame(1234.56, $this->data->getAsDecimal());
    }

    public function testSetRawValueWithScientificNotation(): void
    {
        $this->data->setRawValue('1.5e3');
        Assert::assertSame(1500.0, $this->data->getAsDecimal());
    }

    public function testSetRawValueWithInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot validate 'abc' with filter in Mazarini\BatchBundle\Data\DecimalData.");
        $this->data->setRawValue('abc');
    }

    public function testGetRawValue(): void
    {
        $this->data->setAsDecimal(3.14);
        Assert::assertSame('3.14', $this->data->getRawValue());
    }

    public function testGetRawValueWithFormat(): void
    {
        $this->data->setAsDecimal(3.14159);
        $this->data->setFormat('%.2f');
        Assert::assertSame('3.14', $this->data->getRawValue());
    }
}
