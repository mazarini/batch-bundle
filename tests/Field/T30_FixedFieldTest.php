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
use Mazarini\BatchBundle\Field\FixedField;
use PHPUnit\Framework\TestCase;

class T30_FixedFieldTest extends TestCase
{
    public function testConstructAndGetters(): void
    {
        $dataMock   = self::createMock(DataInterface::class);
        $fixedField = new FixedField(10, 'test_name', $dataMock);
        self::assertSame(10, $fixedField->getLength());
        self::assertSame('test_name', $fixedField->getName());
    }

    public function testSetAndGetStartPosition(): void
    {
        $dataMock   = self::createMock(DataInterface::class);
        $fixedField = new FixedField(10, 'test_name', $dataMock);
        $fixedField->setStartPosition(5);
        self::assertSame(5, $fixedField->getStartPosition());
    }

    public function testGetStartPositionThrowsExceptionWhenNotSet(): void
    {
        self::expectException(\RuntimeException::class);
        $fixedField = new FixedField(10, 'test_name');
        $fixedField->getStartPosition();
    }

    public function testNextPosition(): void
    {
        $dataMock   = self::createMock(DataInterface::class);
        $fixedField = new FixedField(10, 'test_name', $dataMock);
        self::assertSame(15, $fixedField->setStartPosition(5)); // start + length
    }

    public function testIsReady(): void
    {
        $fixedField = new FixedField(10, 'test_name');
        self::assertFalse($fixedField->isReady());
        $dataMock   = self::createMock(DataInterface::class);
        $fixedField->setData($dataMock);
        self::assertFalse($fixedField->isReady());
        $fixedField->setStartPosition(1);
        self::assertTrue($fixedField->isReady());
    }
}
