<?php

namespace App\Enums;

enum CategoryType: string
{
    case Product = 'product';
    case Service = 'service';
    case Digital = 'digital';
    case Mixed = 'mixed';

    public function label(): string
    {
        return match($this) {
            self::Product => 'Product',
            self::Service => 'Service',
            self::Digital => 'Digital',
            self::Mixed => 'Mixed',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_map(fn($case) => $case->label(), self::cases())
        );
    }
}
