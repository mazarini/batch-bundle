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
use Mazarini\BatchBundle\Exception\MisFormatedException;

/**
 * @extends MixedDataAbstract<\DateTimeImmutable>
 *
 * @internal This class is internal to the BatchBundle
 */
class DateTimeData extends MixedDataAbstract
{
    private string $inputFormat;

    /**
     * Initialize datetime data with custom format.
     */
    public function __construct(string $inputFormat = 'Y-m-d H:i:s')
    {
        parent::__construct(TypeEnum::DATE_TIME);
        $this->inputFormat = $inputFormat;
    }

    /**
     * Get raw value formatted as string.
     */
    public function getRawValue(): string
    {
        return $this->isNull() ? '' : $this->value->format($this->inputFormat);
    }

    /**
     * Set raw value by parsing datetime string.
     */
    public function setRawValue(?string $rawValue): static
    {
        if ($rawValue === null) {
            return $this->setNull();
        }

        $dateTime = \DateTimeImmutable::createFromFormat($this->inputFormat, $rawValue);
        if ($dateTime === false) {
            throw new MisFormatedException($rawValue, $this->inputFormat, static::class);
        }

        $this->value = $dateTime;

        return $this;
    }

    /**
     * Gets the datetime value, throws exception if null.
     */
    public function getAsDateTime(): \DateTimeImmutable
    {
        if ($this->isNull()) {
            throw new \InvalidArgumentException('Value is null');
        }

        return $this->value;
    }

    /**
     * Sets the datetime value.
     */
    public function setAsDateTime(\DateTimeImmutable $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Sets the input format for parsing and formatting.
     */
    public function setInputFormat(string $inputFormat): static
    {
        $this->inputFormat = $inputFormat;

        return $this;
    }
}
