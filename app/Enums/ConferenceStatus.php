<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;

enum ConferenceStatus: string implements HasColor, HasDescription, HasIcon
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
    case Cancelled = 'cancelled';

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Draft => 'heroicon-o-pencil',
            self::Published => 'heroicon-o-eye',
            self::Archived => 'heroicon-o-archive-box',
            self::Cancelled => 'heroicon-c-x-circle',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Draft => __('The conference is in draft mode and not published.'),
            self::Published => __('This has been approved by a staff member and is public on the website.'),
            self::Archived => __('This is no longer public on the website.'),
            self::Cancelled => __('This has been cancelled and will not be held.'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'success',
            self::Archived => 'info',
            self::Cancelled => 'danger',
        };
    }
}
