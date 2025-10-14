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
use Mazarini\BatchBundle\Enum\InputTypeEnum;
use Mazarini\BatchBundle\Enum\TypeEnum;

/**
 * @internal This class is internal to the BatchBundle
 */
class Field implements FieldInterface
{
    private string $inputName;
    private InputTypeEnum $inputType;

    /**
     * Field constructor.
     *
     * @param string             $name the name of the field, used as its identifier
     * @param DataInterface|null $data the initial Data object to encapsulate
     */
    public function __construct(
        private string $name,
        ?string $inputName = null,
        ?InputTypeEnum $inputType = null,
        private ?DataInterface $data = null
    ) {
        $this->inputName = $inputName ?? $this->name;
        $this->inputType = $inputType ?? InputTypeEnum::AUTO;
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

    public function getInputName(): string
    {
        return $this->inputName;
    }

    public function getInputType(): InputTypeEnum
    {
        return $this->inputType;
    }

    /**
     * Sets the value of the field's Data object based on the input Data and input type.
     *
     * @param DataInterface $inputData the input Data object
     *
     * @throws \RuntimeException         if the field's Data object is not configured
     * @throws \InvalidArgumentException if the input type is unsupported
     */
    public function setValue(DataInterface $inputData): static
    {
        $outputData = $this->getData();

        switch (true) {
            case InputTypeEnum::RAW === $this->inputType:
                // Raw input, transfer raw value
                $outputData->setRawValue($inputData->getRawValue());
                break;
            case $this->byValue($inputData, $outputData): // InputTypeEnum::VALUE
                break;
            case InputTypeEnum::CAST === $this->inputType && $inputData->isNull():
                $outputData->setNull();
                break;
            case InputTypeEnum::CAST === $this->inputType:
                $outputData->setRawValue($inputData->getRawValue());
                break;
            case InputTypeEnum::MANUAL === $this->inputType:
                // Manual input, value is set externally, no action needed here.
                break;
            case InputTypeEnum::AUTO === $this->inputType && $inputData === $outputData:
                // Automatic input, value is expected to be already set or handled upstream, no action needed here.
                break;
            default:
                throw new \InvalidArgumentException(\sprintf('Unsupported input type "%s" for field "%s".', $this->inputType->value, $this->getName()));
        }

        return $this;
    }

    private function byValue(DataInterface $inputData, DataInterface $outputData): bool
    {
        if (InputTypeEnum::VALUE !== $this->inputType) {
            return false;
        }
        switch ($outputData->getType()) {
            case TypeEnum::DATE_TIME:
                $outputData->setAsDateTimeOrNull($inputData->getAsDateTimeOrNull());
                break;
            case TypeEnum::STRING:
                $outputData->setAsStringOrNull($inputData->getAsStringOrNull());
                break;
            case TypeEnum::INTEGER:
                $outputData->setAsIntegerOrNull($inputData->getAsIntegerOrNull());
                break;
            case TypeEnum::DECIMAL:
                $outputData->setAsDecimalOrNull($inputData->getAsDecimalOrNull());
                break;
            case TypeEnum::BOOLEAN:
                $outputData->setAsBooleanOrNull($inputData->getAsBooleanOrNull());
                break;
            default:
                return false;
        }

        return true;
    }
}
