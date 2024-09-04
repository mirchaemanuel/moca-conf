<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TalkType: string implements HasColor, HasIcon, HasDescription, HasLabel
{
    case Keynote = 'keynote';
    case Workshop = 'workshop';
    case LightningTalk = 'lightning-talk';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Keynote => 'primary',
            self::Workshop => 'info',
            self::LightningTalk => 'success',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::Keynote => 'A talk that establishes a main underlying theme.',
            self::Workshop => 'A meeting at which a group of people engage in intensive discussion and activity on a particular subject or project.',
            self::LightningTalk => 'A very short presentation lasting only a few minutes.',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Keynote => 'heroicon-o-star',
            self::Workshop => 'heroicon-o-cog',
            self::LightningTalk => 'heroicon-o-bolt',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Keynote => 'Keynote',
            self::Workshop => 'Workshop',
            self::LightningTalk => 'Lightning Talk',
        };
    }
}
