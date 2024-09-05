<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TalkStatus: string implements HasDescription, HasColor, HasIcon, HasLabel
{
    case Submitted = 'submitted';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';

    public function getDescription(): ?string
    {
        return match ($this) {
            self::Submitted => 'The talk has been submitted and is pending review.',
            self::Accepted => 'The talk has been accepted and will be given at the conference.',
            self::Rejected => 'The talk has been rejected and will not be given at the conference.',
            self::Cancelled => 'The talk has been cancelled and will not be given at the conference.',
        };
    }


    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Submitted => 'info',
            self::Accepted => 'success',
            self::Rejected => 'danger',
            self::Cancelled => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Submitted => 'heroicon-o-document',
            self::Accepted => 'heroicon-o-check-circle',
            self::Rejected => 'heroicon-o-x-circle',
            self::Cancelled => 'heroicon-o-trash',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Submitted => 'Submitted',
            self::Accepted => 'Accepted',
            self::Rejected => 'Rejected',
            self::Cancelled => 'Cancelled',
        };
    }
}
