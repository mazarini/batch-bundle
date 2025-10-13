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

namespace Mazarini\BatchBundle\Field;

use Mazarini\BatchBundle\Contract\DataInterface;
use Mazarini\BatchBundle\Contract\FieldInterface;

/**
 * @internal This class is internal to the BatchBundle
 */
class Field implements FieldInterface
{
    /**
     * Field constructor.
     *
     * @param string             $name the name of the field, used as its identifier
     * @param DataInterface|null $data the initial Data object to encapsulate
     */
    public function __construct(
        private string $name,
        private ?DataInterface $data = null
    ) {
    }

    /**
     * Gets the name of the field.
     *
     * @return string the identifier of the field
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Retrieves the Data object encapsulated by the field.
     *
     * @throws \RuntimeException if no Data object is set
     */
    public function getData(): DataInterface
    {
        if (null === $this->data) {
            $message = \sprintf('Field "%s" is not configured. $data is undefined', $this->getName());
            throw new \RuntimeException($message);
        }

        return $this->data;
    }

    /**
     * Sets or replaces the Data object for the field.
     *
     * @param DataInterface $data the Data object to set
     */
    public function setData(DataInterface $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Checks if the field contains a Data object.
     *
     * @return bool returns true if no Data object is set, false otherwise
     */
    public function isReady(): bool
    {
        return null !== $this->data;
    }

    public function reset(): static
    {
        if (null !== $this->data) {
            $this->data->reset();
        }

        return $this;
    }
}
