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
 * @extends ScalarDataAbstract<bool>
 *
 * @internal This class is internal to the BatchBundle
 */
class BooleanData extends ScalarDataAbstract
{
    /**
     * Initialize boolean data with FILTER_VALIDATE_BOOLEAN.
     */
    public function __construct()
    {
        parent::__construct(TypeEnum::BOOLEAN, \FILTER_VALIDATE_BOOLEAN, []);
    }

    /**
     * Override getRawValue to return '1'/'0' or ' ' for null.
     */
    public function getRawValue(): string
    {
        return $this->isNull() ? ' ' : ($this->value ? '1' : '0');
    }

    /**
     * Gets the boolean value, throws exception if null.
     */
    public function getAsBoolean(): bool
    {
        if ($this->isNull()) {
            throw new \InvalidArgumentException('Value is null');
        }

        return $this->value;
    }

    /**
     * Sets the boolean value.
     */
    public function setAsBoolean(bool $value): static
    {
        $this->value = $value;

        return $this;
    }
}
