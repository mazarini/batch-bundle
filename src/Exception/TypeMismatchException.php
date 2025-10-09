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

namespace Mazarini\BatchBundle\Exception;

class TypeMismatchException extends \BadMethodCallException
{
    public function __construct(string $currentType, string $methodName, string $expectedType, string $className, int $code = 0, ?\Throwable $previous = null)
    {
        $message = \sprintf(
            'Type mismatch: The parameter type is "%s", but the method called (%s) expects "%s". The function is disabled in %s.',
            $currentType,
            $methodName,
            $expectedType,
            $className
        );
        parent::__construct($message, $code, $previous);
    }
}
