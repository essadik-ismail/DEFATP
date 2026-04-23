<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin      = 'admin';
    case Central    = 'central';     // Direction Centrale — national oversight
    case Dranef     = 'dranef';      // Direction Régionale — approves prorogations, issues mainlevée
    case Dpanef     = 'dpanef';      // Direction Provinciale — supervises provincial operations
    case ZdtfDpanef = 'zdtfdpanef'; // Superset: union of zdtf + dpanef + cpf permissions
    case Zdtf       = 'zdtf';        // Zone de Travaux Forestiers — creates/manages articles
    case Cpf        = 'cpf';         // Commission Provinciale des Forêts — submits PV de récolement
    case Brigade    = 'brigade';     // Brigade — declares vehicles, issues colportage permits
    case Dfp        = 'dfp';         // District Forestier Provincial — same field scope as Brigade

    public function label(): string
    {
        return match ($this) {
            self::Admin      => 'Administrateur',
            self::Central    => 'Direction Centrale',
            self::Dranef     => 'DRANEF',
            self::Dpanef     => 'DPANEF',
            self::ZdtfDpanef => 'ZDTF/DPANEF',
            self::Zdtf       => 'ZDTF',
            self::Cpf        => 'CPF',
            self::Brigade    => 'Brigade',
            self::Dfp        => 'DFP',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
