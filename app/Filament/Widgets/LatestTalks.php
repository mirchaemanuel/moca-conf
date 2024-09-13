<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TalkResource;
use App\Models\Talk;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTalks extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 9;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TalkResource::getEloquentQuery()->latest()->limit(5)
            )
            ->defaultSort('created_at', 'desc')
            ->searchable(false)
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('speaker.fullName'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
            ->actions([
                Tables\Actions\Action::make(__('View'))
                    ->icon('heroicon-o-eye')
                    ->url(fn(Talk $record): string => TalkResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
