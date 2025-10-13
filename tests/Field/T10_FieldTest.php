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
use Mazarini\BatchBundle\Data\StringData;
use Mazarini\BatchBundle\Field\Field;
use PHPUnit\Framework\TestCase;

class T10_FieldTest extends TestCase
{
    public function testConstructAndGetName(): void
    {
        $field = new Field('test_name');
        self::assertSame('test_name', $field->getName());
    }

    public function testSetAndGetData(): void
    {
        $dataMock = $this->createMock(DataInterface::class);
        $field    = new Field('test_name');
        $field->setData($dataMock);
        self::assertSame($dataMock, $field->getData());
    }

    public function testIsReady(): void
    {
        $field = new Field('test_name');
        self::assertFalse($field->isReady());

        $dataMock = $this->createMock(DataInterface::class);
        $field->setData($dataMock);
        self::assertTrue($field->isReady());
    }

    public function testReset(): void
    {
        $data  = new StringData()->setAsString('test');
        $field = new Field('test_name', $data);
        self::assertFalse($data->isNull());
        $field->reset();
        self::assertTrue($data->isNull());
    }

    public function testResetWithNullData(): void
    {
        $field = new Field('test_name');
        $field->reset();
        self::assertFalse($field->isReady());
    }

    public function testGetDataOnNull(): void
    {
        $this->expectException(\RuntimeException::class);
        $field = new Field('test_name');
        $field->getData();
    }
}
