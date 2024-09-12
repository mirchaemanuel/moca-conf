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
                TalkResource::getEloquentQuery()
            )
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->searchable(false)
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('speaker.fullName')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make(__('View'))
                    ->icon('heroicon-o-eye')
                    ->url(fn(Talk $record): string => TalkResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
