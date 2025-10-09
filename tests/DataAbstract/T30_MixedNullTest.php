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

namespace App\Tests\DataAbstract;

use App\Tests\DataAbstract\Stub\MixedDataAbstractStub;
use Mazarini\BatchBundle\Enum\TypeEnum;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class T30_MixedNullTest extends TestCase
{
    /** @var MixedDataAbstractStub<string> */
    private MixedDataAbstractStub $data;

    protected function setUp(): void
    {
        $this->data  = new MixedDataAbstractStub(TypeEnum::STRING);
    }

    public function testInitialStatus(): void
    {
        Assert::assertTrue($this->data->isNull());
        Assert::assertNull($this->data->getAsIntegerOrNull());
        Assert::assertNull($this->data->getAsDecimalOrNull());
        Assert::assertNull($this->data->getAsBooleanOrNull());
        Assert::assertNull($this->data->getAsDateTimeOrNull());
        Assert::assertNull($this->data->getAsStringOrNull());
    }

    public function testNotNull(): void
    {
        $this->data->setValue('Test');
        Assert::assertFalse($this->data->isNull());
        $this->data->setNull();
        Assert::assertTrue($this->data->isNull());
        $this->data->setValue('Test');
        $this->data->reset();
        Assert::assertTrue($this->data->isNull());
    }

    public function testSetNull(): void
    {
        $this->data->setValue('Test');
        $this->data->setAsIntegerOrNull(null);
        Assert::assertTrue($this->data->isNull());

        $this->data->setValue('Test');
        $this->data->setAsDecimalOrNull(null);
        Assert::assertTrue($this->data->isNull());

        $this->data->setValue('Test');
        $this->data->setAsBooleanOrNull(null);
        Assert::assertTrue($this->data->isNull());

        $this->data->setValue('Test');
        $this->data->setAsDateTimeOrNull(null);
        Assert::assertTrue($this->data->isNull());

        $this->data->setValue('Test');
        $this->data->setAsStringOrNull(null);
        Assert::assertTrue($this->data->isNull());
    }
}
