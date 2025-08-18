<?php

namespace App\Utils;

use App\Traits\EnumTrait;
use BackedEnum;
use ErrorException;
use Illuminate\Support\Collection;
use JsonSerializable;
use ReflectionEnum;
use UnitEnum;

class OptionList implements JsonSerializable
{
    public array $options = [];

    public function __construct(string|null $nullOptionLabel = null)
    {
        if (!is_null($nullOptionLabel)) {
            $this->addOption($nullOptionLabel, '');
        }
    }

    public static function make(string|null $nullOptionLabel = null): self
    {
        return new self($nullOptionLabel);
    }

    public function addOption(string $label, string $value, string $group = null): self
    {
        if ($group) {
            if (isset($this->options[$group])) {
                $this->options[$group][] = new Option($label, $value);
            } else {
                $this->options[$group] = [new Option($label, $value)];
            }
        } else {
            $this->options[] = new Option($label, $value);
        }

        return $this;
    }

    public function addFromCollection(Collection $collection, string $labelKey, string $valueKey, string $group = null): self
    {
        foreach ($collection as $record) {
            $this->addOption($record->$labelKey, $record->$valueKey, $group);
        }

        return $this;
    }

    /**
     * @param string $enumClass
     * @return self
     * @throws ErrorException
     */
    public function addFromEnum(string $enumClass, string $group = null): self
    {
        if (!enum_exists($enumClass)) {
            throw new ErrorException("$enumClass does not exist as an enum");
        }

        $enumUsesEnumTrait = in_array(EnumTrait::class, class_uses($enumClass));

        if ((new ReflectionEnum($enumClass))->isBacked()) {
            /** @var class-string<BackedEnum> $enumClass */
            foreach ($enumClass::cases() as $case) {
                $this->addOption(
                    $enumUsesEnumTrait ? $case->title() : $case->name,
                    $case->value,
                    $group
                );
            }
        } else {
            /** @var class-string<UnitEnum> $enumClass */
            foreach ($enumClass::cases() as $case) {
                $this->addOption(
                    $enumUsesEnumTrait ? $case->title() : $case->name,
                    $case->name,
                    $group
                );
            }
        }

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $jsonStructure = [];

        foreach ($this->options as $key => $option) {
            if (is_numeric($key)) {
                $jsonStructure[] = $option;
            } else {
                $jsonStructure[] = [
                    'label' => $key,
                    'options' => $option,
                ];
            }
        }

        return $jsonStructure;
    }
}
