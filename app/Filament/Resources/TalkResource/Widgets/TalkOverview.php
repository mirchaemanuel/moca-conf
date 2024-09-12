<?php

namespace App\Filament\Resources\TalkResource\Widgets;

use App\Enums\TalkStatus;
use App\Filament\Resources\TalkResource\Pages\ListTalks;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TalkOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListTalks::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(__('Talks'), $this->getPageTableQuery()->count())
                ->icon('heroicon-o-document-text'),
            Stat::make(__('Submitted'), $this->getPageTableQuery()->where('status', '=', TalkStatus::Submitted->value)->count())
                ->icon(TalkStatus::Submitted->getIcon()),
            Stat::make(__('Accepted'), $this->getPageTableQuery()->where('status', '=', TalkStatus::Accepted->value)->count())
                ->icon(TalkSTatus::Accepted->getIcon()),
            Stat::make(__('Avg Duration'), number_format((float)($this->getPageTableQuery()->avg('duration') ?? 0), 2))
                ->icon('heroicon-o-clock')
        ];
    }
}
