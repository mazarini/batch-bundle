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

use Mazarini\BatchBundle\Exception\MisFormatedException;
use PhpParser\Node\Scalar;

/**
 * Abstract base class for scalar data types (int, float, bool, string)
 * that can be validated with filter_var() and cast to string.
 *
 * @template T of int|float|bool|string
 *
 * @extends MixedDataAbstract<T>
 *
 * @internal This class is internal to the BatchBundle
 */
abstract class ScalarDataAbstract extends MixedDataAbstract
{
    protected int $filter;
    /**
     * @var array{flags?: int, options?: array<string, mixed>}
     */
    protected array $options;

    /**
     * Initialize scalar data with filter and options, automatically adding FILTER_NULL_ON_FAILURE.
     *
     * @param array{flags?: int, options?: array<string, mixed>} $options
     */
    public function __construct(\Mazarini\BatchBundle\Enum\TypeEnum $type, int $filter, array $options = [])
    {
        parent::__construct($type);
        $this->filter           = $filter;
        $this->options          = $options;
        $this->options['flags'] =  ($this->options['flags'] ?? 0) | \FILTER_NULL_ON_FAILURE;
    }

    /**
     * Get the raw scalar value with optional formatting using formatScalarValue.
     */
    public function getRawValue(): string
    {
        return $this->formatScalarValue($this->value);
    }

    /**
     * Set raw value by delegating to validateWithFilter.
     *
     * @param string|null $rawValue The raw string value or null
     */
    public function setRawValue(?string $rawValue): static
    {
        return $rawValue === null ? $this->setNull() : $this->validateWithFilter($rawValue);
    }

    /**
     * Format scalar value using sprintf or string cast.
     *
     * @param T $value
     *
     * @return string scalar value after cast or format
     */
    protected function formatScalarValue(mixed $value): string
    {

        return $this->format === null ? (string) $value : \sprintf($this->format, $value);
    }

    /**
     * Clean raw value by trimming whitespace.
     *
     * @param string $rawValue The raw string value to clean
     */
    protected function clean(string $rawValue): string
    {
        return \mb_trim($rawValue);
    }

    /**
     * Validate string value using filter_var with class properties.
     *
     * @param string|null $rawValue The raw string value to validate
     *
     * @throws MisFormatedException If validation fails
     */
    protected function validateWithFilter(?string $rawValue): static
    {
        if ($rawValue === null) {
            return $this->setNull();
        }

        $cleanValue = $this->clean($rawValue);
        $value      = \filter_var($cleanValue, $this->filter, $this->options);
        if ($value === null) {
            throw new MisFormatedException($rawValue, 'filter', static::class);
        }

        $this->value = $value;
        // Value is set, so it's not null anymore

        return $this;
    }
}
