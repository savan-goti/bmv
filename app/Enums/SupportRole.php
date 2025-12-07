<?php

namespace App\Enums;

enum SupportRole: string
{
    case ADMIN = 'admin';
    case STAFF = 'staff';
    case SELLER = 'seller';
    case CUSTOMER = 'customer';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Admin',
            self::STAFF => 'Staff',
            self::SELLER => 'Seller',
            self::CUSTOMER => 'Customer',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label()
        ], self::cases());
    }
}
