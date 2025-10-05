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
class IntegerData extends DataAbstract
{
    private int $value;
    private int $filter;
    /**
     * @var array<string, mixed>
     */
    private array $options;

    public function __construct(int $filter = \FILTER_VALIDATE_INT, ?int $minRange = null, ?int $maxRange = null)
    {
        parent::__construct(TypeEnum::INTEGER);
        $this->filter  = $filter;
        $this->options = [];

        if ($minRange !== null || $maxRange !== null) {
            $this->options['options'] = [];
            if ($minRange !== null) {
                $this->options['options']['min_range'] = $minRange;
            }
            if ($maxRange !== null) {
                $this->options['options']['max_range'] = $maxRange;
            }
        }
    }

    protected function resetValue(): static
    {
        unset($this->value);

        return $this;
    }

    public function getRawValue(): string
    {
        return $this->formatScalarValue($this->value);
    }

    public function setRawValue(?string $rawValue): static
    {
        if ($rawValue === null) {
            unset($this->value);

            return $this->setNull();
        }

        $cleanValue = \ltrim(\trim($rawValue), '0') ?: '0';
        $intValue = $this->options === []
            ? \filter_var($cleanValue, $this->filter)
            : \filter_var($cleanValue, $this->filter, $this->options);
        if ($intValue === false) {
            throw new \InvalidArgumentException("Cannot convert '{$rawValue}' to integer");
        }

        $this->value    = $intValue;
        $this->setNull(false);

        return $this;
    }

    public function getAsInteger(): int
    {
        if ($this->isNull()) {
            throw new \InvalidArgumentException('Value is null');
        }

        return $this->value;
    }

    public function getAsIntegerOrNull(): ?int
    {
        return $this->isNull() ? null : $this->value;
    }

    public function setAsInteger(int $value): static
    {
        $this->value    = $value;
        $this->setNull(false);

        return $this;
    }

    public function setAsIntegerOrNull(?int $value): static
    {
        if ($value === null) {
            return $this->setNull();
        }

        return $this->setAsInteger($value);
    }
}
