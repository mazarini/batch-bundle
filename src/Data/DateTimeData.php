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
class DateTimeData extends DataAbstract
{
    private \DateTimeImmutable $value;
    private string $dateFormat = 'Y-m-d H:i:s';

    public function __construct(string $dateFormat = 'Y-m-d H:i:s')
    {
        parent::__construct(TypeEnum::DATE_TIME);
        $this->dateFormat = $dateFormat;
    }

    protected function resetValue(): static
    {
        unset($this->value);

        return $this;
    }

    public function getRawValue(): string
    {
        return $this->isNull() ? '' : $this->formatValue($this->value);
    }

    public function setRawValue(?string $rawValue): static
    {
        if ($rawValue === null) {
            return $this->setNull();
        }

        $dateTime = \DateTimeImmutable::createFromFormat($this->dateFormat, $rawValue);
        if ($dateTime === false) {
            throw new \InvalidArgumentException("Cannot convert '{$rawValue}' to datetime with format '{$this->dateFormat}'");
        }

        $this->value = $dateTime;
        $this->setNull(false);

        return $this;
    }

    public function getAsDateTime(): \DateTimeImmutable
    {
        if ($this->isNull()) {
            throw new \InvalidArgumentException('Value is null');
        }

        return $this->value;
    }

    public function getAsDateTimeOrNull(): ?\DateTimeImmutable
    {
        return $this->isNull() ? null : $this->value;
    }

    public function setAsDateTime(\DateTimeImmutable $value): static
    {
        $this->value = $value;
        $this->setNull(false);

        return $this;
    }

    public function setAsDateTimeOrNull(?\DateTimeImmutable $value): static
    {
        if ($value === null) {
            return $this->setNull();
        }

        return $this->setAsDateTime($value);
    }

    protected function formatValue(\DateTimeImmutable $value): string
    {
        return $value->format($this->dateFormat);
    }
}
