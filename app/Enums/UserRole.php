<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin       = 'admin';
    case Central     = 'central';      // Direction Centrale — national oversight
    case Dranef      = 'dranef';       // Direction Régionale — regional management
    case Dpanef      = 'dpanef';       // Direction Provinciale — document saisie specialist
    case ZdtfDpanef  = 'zdtfdpanef';  // Superset: union of dpanef + cpf_zdtf permissions
    case CpfZdtf     = 'cpf_zdtf';    // Field post — permits only (replaces separate cpf/zdtf)
    case BrigadeDfp  = 'brigade_dfp'; // Field control — read-only + limited download
    // Legacy roles kept for backward compatibility; mapped to the same permissions as their replacements
    case Zdtf        = 'zdtf';
    case Cpf         = 'cpf';
    case Brigade     = 'brigade';
    case Dfp         = 'dfp';

    public function label(): string
    {
        return match ($this) {
            self::Admin      => 'Administrateur',
            self::Central    => 'Direction Centrale',
            self::Dranef     => 'DRANEF',
            self::Dpanef     => 'DPANEF',
            self::ZdtfDpanef => 'ZDTF/DPANEF',
            self::CpfZdtf    => 'CPF/ZDTF',
            self::BrigadeDfp => 'Brigade/DFP',
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
