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

use Mazarini\BatchBundle\Data\BooleanData;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class T60_BooleanDataTest extends TestCase
{
    private BooleanData $data;

    protected function setUp(): void
    {
        $this->data = new BooleanData();
    }

    #[DataProvider('validBooleanProvider')]
    public function testSetRawValueWithValidBooleans(string $input, bool $expected, string $expectedRaw): void
    {
        $this->data->setRawValue($input);
        Assert::assertSame($expected, $this->data->getAsBoolean());
        Assert::assertSame($expectedRaw, $this->data->getRawValue());
    }

    #[DataProvider('invalidBooleanProvider')]
    public function testSetRawValueWithInvalidBooleans(string $input): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->data->setRawValue($input);
    }

    public function testNullValue(): void
    {
        $this->data->setRawValue(null);
        Assert::assertNull($this->data->getAsBooleanOrNull());
        Assert::assertSame(' ', $this->data->getRawValue());
    }

    public function testSetAsBooleanOrNullWithTrue(): void
    {
        $this->data->setAsBooleanOrNull(true);
        Assert::assertTrue($this->data->getAsBooleanOrNull());
        Assert::assertSame('1', $this->data->getRawValue());
    }

    public function testSetAsBooleanOrNullWithFalse(): void
    {
        $this->data->setAsBooleanOrNull(false);
        Assert::assertFalse($this->data->getAsBooleanOrNull());
        Assert::assertSame('0', $this->data->getRawValue());
    }

    public function testSetAsBooleanOrNullWithNull(): void
    {
        $this->data->setAsBooleanOrNull(null);
        Assert::assertNull($this->data->getAsBooleanOrNull());
        Assert::assertSame(' ', $this->data->getRawValue());
    }

    public function testGetAsBooleanWhenNull(): void
    {
        $this->data->setRawValue(null);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value is null');
        $this->data->getAsBoolean();
    }

    public static function validBooleanProvider(): \Iterator
    {
        yield 'String true'  => ['true',  true,  '1'];
        yield 'String false' => ['false', false, '0'];
        yield 'String 1'     => ['1',     true,  '1'];
        yield 'String 0'     => ['0',     false, '0'];
        yield 'String yes'   => ['yes',   true,  '1'];
        yield 'String no'    => ['no',    false, '0'];
        yield 'String on'    => ['on',    true,  '1'];
        yield 'String off'   => ['off',   false, '0'];
    }

    public static function invalidBooleanProvider(): \Iterator
    {
        yield 'Invalid string' => ['invalid'];
        yield 'Number 2'       => ['2'];
        yield 'Random text'    => ['abc'];
        yield 'Special chars'  => ['@#$'];
    }
}
