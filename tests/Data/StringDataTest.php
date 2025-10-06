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

use Mazarini\BatchBundle\Data\StringData;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class StringDataTest extends TestCase
{
    private StringData $data;

    protected function setUp(): void
    {
        $this->data = new StringData();
    }

    public function testGetAsString(): void
    {
        $this->data->setAsString('test');
        Assert::assertSame('test', $this->data->getAsString());
    }

    public function testGetAsStringThrowsExceptionWhenNull(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value is null');
        $this->data->getAsString();
    }

    public function testGetAsStringOrNull(): void
    {
        Assert::assertNull($this->data->getAsStringOrNull());

        $this->data->setAsString('test');
        Assert::assertSame('test', $this->data->getAsStringOrNull());
    }

    public function testSetAsStringWithTrimming(): void
    {
        $this->data->setAsString('  hello world  ');
        Assert::assertSame('hello world', $this->data->getAsString());
    }

    public function testSetAsStringOrNull(): void
    {
        $this->data->setAsStringOrNull(null);
        Assert::assertTrue($this->data->isNull());

        $this->data->setAsStringOrNull('  test  ');
        Assert::assertSame('test', $this->data->getAsString());
    }

    public function testSetRawValue(): void
    {
        $this->data->setRawValue('  raw value  ');
        Assert::assertSame('raw value', $this->data->getAsString());

        $this->data->setRawValue(null);
        Assert::assertTrue($this->data->isNull());
    }

    public function testGetRawValue(): void
    {
        Assert::assertSame('', $this->data->getRawValue());

        $this->data->setAsString(' test ');
        Assert::assertSame('test', $this->data->getRawValue());
    }

    public function testGetRawValueWithLeftAlignment(): void
    {
        $this->data->setAsString('hello');
        $this->data->setFormat('%-10s');
        Assert::assertSame('hello     ', $this->data->getRawValue());
    }

    public function testGetRawValueWithRightAlignment(): void
    {
        $this->data->setAsString('hello');
        $this->data->setFormat('%10s');
        Assert::assertSame('     hello', $this->data->getRawValue());
    }

    public function testNullFormated(): void
    {
        $this->data->setFormat('%2s');
        Assert::assertSame('  ', $this->data->getRawValue());
    }

    public function testGetRawValueWithTruncation(): void
    {
        $this->data->setAsString('HELLO');
        $this->data->setFormat('%.3s');
        Assert::assertSame('HEL', $this->data->getRawValue());
    }

    public function testGetRawValueWithPadding(): void
    {
        $this->data->setAsString('HI');
        $this->data->setFormat('%5s');
        Assert::assertSame('   HI', $this->data->getRawValue());

        $this->data->setFormat('%-5s');
        Assert::assertSame('HI   ', $this->data->getRawValue());
    }

    public function testGetRawValueWithFixedWidthLeftAligned(): void
    {
        // Chaîne courte : padding à droite pour faire 5 caractères
        $this->data->setAsString('HI');
        $this->data->setFormat('%-5.5s');
        Assert::assertSame('HI   ', $this->data->getRawValue());

        // Chaîne longue : troncature à 5 caractères exactement
        $this->data->setAsString('HELLO WORLD');
        $this->data->setFormat('%-5.5s');
        Assert::assertSame('HELLO', $this->data->getRawValue());
    }
}
