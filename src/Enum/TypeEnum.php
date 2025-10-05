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

namespace Mazarini\BatchBundle\Enum;

/**
 * Defines the fundamental and fixed data types for a specific Job parameter.
 * This enum is used to declare the expected type of a Job parameter object upon creation.
 *
 * @internal This class is internal to the BatchBundle and should not be extended or used directly outside the bundle
 */
enum TypeEnum: string
{
    // Integer number
    case INTEGER = 'integer';

    // Floating-point number (decimal)
    case DECIMAL = 'decimal';

    // Date and/or time value
    case DATE_TIME = 'datetime';

    // Boolean value (true or false)
    case BOOLEAN = 'boolean';

    // String of characters (text)
    case STRING = 'string';

    /**
     * Checks if the current type represents a numeric value (integer or decimal).
     */
    public function isNumeric(): bool
    {
        return match ($this) {
            self::INTEGER, self::DECIMAL => true,
            default => false
        };
    }
}
