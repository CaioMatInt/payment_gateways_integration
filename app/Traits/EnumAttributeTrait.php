<?php

namespace App\Traits;

trait EnumAttributeTrait {
    public static function values(): array
    {
        return array_map(function ($case) {
            return $case->value;
        }, self::cases());
    }
}