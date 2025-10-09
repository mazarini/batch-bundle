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

use App\Tests\DataAbstract\Stub\ScalarDataAbstractStub;
use Mazarini\BatchBundle\Enum\TypeEnum;
use Mazarini\BatchBundle\Exception\MisFormatedException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class T40_ScalarTest extends TestCase
{
    /** @var ScalarDataAbstractStub<float> */
    private ScalarDataAbstractStub $data;

    protected function setUp(): void
    {
        $this->data  = new ScalarDataAbstractStub(TypeEnum::DECIMAL, \FILTER_VALIDATE_FLOAT);
    }

    public function testMisFormatedException(): void
    {
        $this->expectException(MisFormatedException::class);
        $this->expectExceptionMessage("Cannot validate 'not a float' with filter in App\\Tests\\DataAbstract\\Stub\\ScalarDataAbstractStub.");
        $this->data->setRawValue('not a float');
    }

    public function testSetRawValue(): void
    {
        $this->data->setRawValue('99.123456');
        Assert::assertSame('99.123456', $this->data->getRawValue());
        $this->data->setRawValue(null);
        Assert::assertTrue($this->data->isNull());
    }

    public function testFormat(): void
    {
        $this->data
            ->setRawValue('99.123456')
            ->setFormat('%06.2f');
        Assert::assertSame('099.12', $this->data->getRawValue());
    }
}
