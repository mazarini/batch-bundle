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

class DataAbstractTest extends TestCase
{
    private IntegerData $data;

    protected function setUp(): void
    {
        $this->data = new IntegerData();
    }

    public function testIsNullByDefault(): void
    {
        Assert::assertTrue($this->data->isNull());
    }

    public function testSetNull(): void
    {
        Assert::assertTrue($this->data->isNull());

        $this->data->setNull(false);
        Assert::assertFalse($this->data->isNull());

        $this->data->setNull(true);
        Assert::assertTrue($this->data->isNull());
    }

    public function testReset(): void
    {
        $this->data->setNull(false);
        $this->data->setAsInteger(42);
        $this->data->reset();
        Assert::assertTrue($this->data->isNull());
        Assert::assertNull($this->data->getAsIntegerOrNull());
    }

    public function testSetFormat(): void
    {
        $this->data->setAsInteger(42);
        Assert::assertSame('42', $this->data->getRawValue());
        $this->data->setFormat('%05d');
        Assert::assertSame('00042', $this->data->getRawValue());
    }
}
