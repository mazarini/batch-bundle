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

use Mazarini\BatchBundle\Data\DateTimeData;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DateTimeDataTest extends TestCase
{
    private DateTimeData $data;

    protected function setUp(): void
    {
        $this->data = new DateTimeData();
    }

    #[DataProvider('validDateProvider')]
    public function testSetRawValueWithValidDates(string $input, string $expectedRawValue, ?string $inputFormat = null): void
    {
        $data = $inputFormat !== null ? new DateTimeData($inputFormat) : $this->data;
        $data->setRawValue($input);
        Assert::assertSame($expectedRawValue, $data->getRawValue());
    }

    #[DataProvider('invalidDateProvider')]
    public function testSetRawValueWithInvalidDates(string $input): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->data->setRawValue($input);
        $this->data->getAsDateTime();
    }

    public function testCustomFormat(): void
    {
        $data = new DateTimeData('d/m/Y');
        $data->setRawValue('25/12/2023');
        $dateTime = $data->getAsDateTime();
        Assert::assertSame('2023-12-25', $dateTime->format('Y-m-d'));
    }

    public function testNullValue(): void
    {
        $this->data->setRawValue(null);
        Assert::assertNull($this->data->getAsDateTimeOrNull());
    }

    public function testSetAsDateTimeOrNullWithDate(): void
    {
        $dateTime = new \DateTimeImmutable('2023-12-25 14:30:00');
        $this->data->setAsDateTimeOrNull($dateTime);
        Assert::assertSame($dateTime, $this->data->getAsDateTimeOrNull());
        Assert::assertSame('2023-12-25 14:30:00', $this->data->getRawValue());
    }

    public function testSetAsDateTimeOrNullWithNull(): void
    {
        $this->data->setAsDateTimeOrNull(null);
        Assert::assertNull($this->data->getAsDateTimeOrNull());
        Assert::assertSame('', $this->data->getRawValue());
    }

    public function testGetAsDateTimeWhenNull(): void
    {
        $this->data->setRawValue(null);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value is null');
        $this->data->getAsDateTime();
    }

    public static function validDateProvider(): \Iterator
    {
        yield 'ISO with time' => ['2023-12-25 14:30:00', '2023-12-25 14:30:00'];
        yield 'ISO with seconds' => ['2023-01-15 09:45:30', '2023-01-15 09:45:30'];
        yield 'New Year' => ['2024-01-01 00:00:00', '2024-01-01 00:00:00'];
        yield 'Leap year' => ['2024-02-29 12:00:00', '2024-02-29 12:00:00'];
        yield 'European format' => ['25/12/2023', '25/12/2023', 'd/m/Y'];
        yield 'US format' => ['12/25/2023', '12/25/2023', 'm/d/Y'];
        yield 'German format' => ['25.12.2023', '25.12.2023', 'd.m.Y'];
        yield 'French format with time' => ['25/12/2023 14:30', '25/12/2023 14:30', 'd/m/Y H:i'];
        yield 'Time only HH:MM' => ['14:30', '14:30', 'H:i'];
        yield 'Time only HH:MM:SS' => ['09:45:30', '09:45:30', 'H:i:s'];
        yield 'Time 12h format' => ['02:30 PM', '02:30 PM', 'h:i A'];
        yield 'Time midnight' => ['00:00:00', '00:00:00', 'H:i:s'];
    }

    public static function invalidDateProvider(): \Iterator
    {
        yield 'Wrong format' => ['25/12/2023'];
        yield 'Letters' => ['abc'];
        yield 'Empty string' => [''];
        yield 'Missing time' => ['2023-12-25'];
        yield 'Invalid format' => ['2023/12/25 14:30:00'];
        yield 'Malformed' => ['2023-12-25T14:30'];
    }
}
