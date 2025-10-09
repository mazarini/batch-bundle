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
 * @extends ScalarDataAbstract<float>
 *
 * @internal This class is internal to the BatchBundle
 */
class DecimalData extends ScalarDataAbstract
{
    /**
     * Initialize decimal data with FILTER_VALIDATE_FLOAT and thousand/scientific notation support.
     */
    public function __construct()
    {
        $options = [
            'flags' => \FILTER_FLAG_ALLOW_THOUSAND | \FILTER_FLAG_ALLOW_SCIENTIFIC,
        ];

        parent::__construct(TypeEnum::DECIMAL, \FILTER_VALIDATE_FLOAT, $options);
    }

    /**
     * Gets the decimal value, throws exception if null.
     */
    public function getAsDecimal(): float
    {
        if ($this->isNull()) {
            throw new \InvalidArgumentException('Value is null');
        }

        return $this->value;
    }

    /**
     * Sets the decimal value.
     */
    public function setAsDecimal(float $value): static
    {
        $this->value = $value;

        return $this;
    }
}
