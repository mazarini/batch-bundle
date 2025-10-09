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

namespace App\Tests\DataAbstract\Stub;

use Mazarini\BatchBundle\Data\DataAbstract;

/**
 * @template T
 *
 * @extends DataAbstract<T>
 */
class DataAbstractStub extends DataAbstract
{
    /* +===============================================+ */
    /* !       Abstract method : don't call them       ! */
    /* +===============================================+ */

    public function isNull(): bool
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function setNull(): static
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function reset(): static
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function setFormat(?string $format): static
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function getAsIntegerOrNull(): ?int
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function setAsIntegerOrNull(?int $value): static
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function getAsDecimalOrNull(): ?float
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function setAsDecimalOrNull(?float $value): static
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function getAsDateTimeOrNull(): ?\DateTimeImmutable
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function setAsDateTimeOrNull(?\DateTimeImmutable $value): static
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function getAsBooleanOrNull(): ?bool
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function setAsBooleanOrNull(?bool $value): static
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function getAsStringOrNull(): ?string
    {
        throw new \LogicException('Method should not be called in this test context.');
    }

    public function setAsStringOrNull(?string $value): static
    {
        throw new \LogicException('Method should not be called in this test context.');
    }
}
