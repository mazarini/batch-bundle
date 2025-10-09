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

use Mazarini\BatchBundle\Data\IntlDecimalData;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class T50_IntlDecimalDataTest extends TestCase
{
    private IntlDecimalData $data;

    protected function setUp(): void
    {
        $this->data = new IntlDecimalData();
    }

    #[DataProvider('europeanFormatProvider')]
    public function testSetRawValueWithEuropeanFormats(?string $input, ?float $expected): void
    {
        $this->data->setRawValue($input);
        Assert::assertSame($expected, $this->data->getAsDecimalOrNull());
    }

    #[DataProvider('invalidFormatProvider')]
    public function testSetRawValueWithInvalidFormats(string $input): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->data->setRawValue($input);
        $this->data->getAsDecimal();
    }

    public static function europeanFormatProvider(): \Iterator
    {
        yield 'Simple comma' => ['123,45', 123.45];
        yield 'Dot thousands' => ['1.234,56', 1234.56];
        yield 'Space thousands' => ['1 234,56', 1234.56];
        yield 'Multiple dots' => ['1.234.567,89', 1234567.89];
        yield 'Multiple spaces' => ['1 234 567,89', 1234567.89];
        yield 'No comma' => ['123.456', 123456.0];
        yield 'Integer' => ['1234', 1234.0];
        yield 'No decimal' => ['123,', 123.0];
        yield 'Zero' => ['0,00', 0.0];
        yield 'Null' => [null, null];
        yield 'Trailing spaces' => ['123,45 ', 123.45];
        yield 'Leading spaces' => [' 123,45', 123.45];
        yield 'Very large number' => ['999.999.999,99', 999999999.99];
        yield 'Small decimal' => ['0,01', 0.01];
        yield 'Three decimals' => ['123,456', 123.456];
        yield 'Single digit' => ['5', 5.0];
    }

    public static function invalidFormatProvider(): \Iterator
    {
        yield 'Letters' => ['abc'];
        yield 'Mixed letters and numbers' => ['12a34'];
        yield 'Multiple commas' => ['123,45,67'];
        yield 'Double comma' => ['123,,45'];
        yield 'Special characters' => ['123@45'];
        yield 'Currency symbol' => ['€123,45'];
        yield 'Percentage' => ['123%'];
        yield 'Scientific notation' => ['1.23e+2'];
        yield 'Negative with wrong separator' => ['-123.45,67'];
        yield 'Multiple dots at end' => ['123..'];
        yield 'Comma in thousands position' => ['1,234.56'];
    }
}
