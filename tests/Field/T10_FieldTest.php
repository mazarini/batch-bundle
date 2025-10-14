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

namespace App\Tests\Field;

use Mazarini\BatchBundle\Contract\DataInterface;
use Mazarini\BatchBundle\Data\BooleanData;
use Mazarini\BatchBundle\Data\DateTimeData;
use Mazarini\BatchBundle\Data\DecimalData;
use Mazarini\BatchBundle\Data\IntegerData;
use Mazarini\BatchBundle\Data\StringData;
use Mazarini\BatchBundle\Enum\InputTypeEnum;
use Mazarini\BatchBundle\Field\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class T10_FieldTest extends TestCase
{
    #[DataProvider('constructProvider')]
    public function testConstruct(
        string $name,
        ?string $inputName,
        ?InputTypeEnum $inputType,
        ?DataInterface $data,
        string $expectedName,
        string $expectedInputName,
        InputTypeEnum $expectedInputType,
        bool $expectedIsReady
    ): void {
        $field = new Field($name, $inputName, $inputType, $data);
        self::assertSame($expectedName, $field->getName());
        self::assertSame($expectedInputName, $field->getInputName());
        self::assertSame($expectedInputType, $field->getInputType());
        self::assertSame($expectedIsReady, $field->isReady());
    }

    public function testSetAndGetData(): void
    {
        $dataMock = $this->createMock(DataInterface::class);
        $field    = new Field('test_name', null, null, null);
        $field->setData($dataMock);
        self::assertSame($dataMock, $field->getData());
    }

    public function testIsReady(): void
    {
        $field = new Field('test_name', null, null, null);
        self::assertFalse($field->isReady());
        $dataMock = $this->createMock(DataInterface::class);
        $field->setData($dataMock);
        self::assertTrue($field->isReady());
    }

    public function testReset(): void
    {
        $data  = new StringData();
        $data->setAsString('test');
        $field = new Field('test_name', null, null, $data);
        self::assertFalse($data->isNull());
        $field->reset();
        self::assertTrue($data->isNull());
    }

    public function testGetDataOnNull(): void
    {
        $field = new Field('test_name', null, null, null);
        $this->expectException(\RuntimeException::class);
        $field->getData();
    }

    #[DataProvider('valueProvider')]
    public function testSetValue(
        InputTypeEnum $inputType,
        DataInterface $inputData,
        ?DataInterface $outputData,
        mixed $value,
        mixed $expectedValue
    ): void {
        $outputData = $outputData === null ? $inputData : $outputData;
        $field      = new Field('name', 'input', $inputType, $outputData);
        if ($value === null) {
            $inputData->setNull();
            $field->setValue($inputData);
            self::assertTrue($outputData->isNull());
        } else {
            $inputData->setRawValue($value);
            $field->setValue($inputData);
            self::assertFalse($outputData->isNull());
            self::assertSame($expectedValue, $outputData->getRawValue());
        }
    }

    public static function constructProvider(): \Iterator
    {
        yield 'full params' => ['name', 'inputName', InputTypeEnum::MANUAL, new StringData()->setAsString('value'),
            'name', 'inputName', InputTypeEnum::MANUAL, true,
        ];
        yield 'missing data value' => ['name', 'inputName', InputTypeEnum::MANUAL, new StringData(),
            'name', 'inputName', InputTypeEnum::MANUAL, true,
        ];
        yield 'missing data' => ['name', 'inputName', InputTypeEnum::MANUAL, null,
            'name', 'inputName', InputTypeEnum::MANUAL, false,
        ];
        yield 'missing input type' => ['name', 'inputName', null, null,
            'name', 'inputName', InputTypeEnum::AUTO, false,
        ];
        yield 'missing input name' => ['name', null, null, null,
            'name', 'name', InputTypeEnum::AUTO, false,
        ];
    }

    public static function valueProvider(): \Iterator
    {
        yield 1  => [InputTypeEnum::RAW, new StringData(), new DecimalData(),  '123.10', '123.1'];
        yield 2  => [InputTypeEnum::AUTO, new StringData(), null,  '123', '123'];
        yield 3  => [InputTypeEnum::CAST, new StringData(), new IntegerData(),  null, true];
        yield 4  => [InputTypeEnum::CAST, new StringData(), new IntegerData(),  '123', '123'];
        yield 5  => [InputTypeEnum::VALUE, new DateTimeData('Y-m'), new DateTimeData('Y/m'), '2025-01', '2025/01'];
        yield 6  => [InputTypeEnum::VALUE, new StringData(), new StringData(), 'abc', 'abc'];
        yield 7  => [InputTypeEnum::VALUE, new IntegerData(), new IntegerData(), '12', '12'];
        yield 8  => [InputTypeEnum::VALUE, new DecimalData(), new DecimalData(), '12.3', '12.3'];
        yield 9  => [InputTypeEnum::VALUE, new BooleanData(), new BooleanData(), 'false', '0'];
        yield 10 => [InputTypeEnum::VALUE, new BooleanData(), new BooleanData(), 'yes', '1'];
    }
}
