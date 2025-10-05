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

namespace Mazarini\BatchBundle\Data;

use Mazarini\BatchBundle\Enum\TypeEnum;

/**
 * @internal This class is internal to the BatchBundle
 */
class StringData extends DataAbstract
{
    private string $value;

    public function __construct()
    {
        parent::__construct(TypeEnum::STRING);
    }

    public function getRawValue(): string
    {
        return $this->isNull() ? '' : $this->formatScalarValue($this->value);
    }

    protected function resetValue(): static
    {
        unset($this->value);

        return $this;
    }

    public function setRawValue(?string $rawValue): static
    {
        if ($rawValue === null) {
            return $this->setNull();
        }

        $this->value = $rawValue;
        $this->setNull(false);

        return $this;
    }

    public function getAsString(): string
    {
        if ($this->isNull()) {
            throw new \InvalidArgumentException('Value is null');
        }

        return $this->value;
    }

    public function getAsStringOrNull(): ?string
    {
        return $this->isNull() ? null : $this->value;
    }

    public function setAsString(string $value): static
    {
        $this->value = $value;
        $this->setNull(false);

        return $this;
    }

    public function setAsStringOrNull(?string $value): static
    {
        if ($value === null) {
            return $this->setNull();
        }

        return $this->setAsString($value);
    }
}
