<?php

namespace App\Enums;

enum UserRole: string
{
    case Agency = 'agency';
    case Admin = 'admin';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Agency => 'Agence',
            self::Admin => 'Administrateur',
        };
    }

    /**
     * All values for validation/selects.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
