<?php

namespace App\Utils;

class Option
{
    public string $label;
    public string $value;

    public function __construct(
        string $label,
        string $value
    )
    {
        $this->label = $label;
        $this->value = $value;
    }
}
