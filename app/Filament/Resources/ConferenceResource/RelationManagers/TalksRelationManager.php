<?php

namespace App\Filament\Resources\ConferenceResource\RelationManagers;

use App\Enums\ConferenceStatus;
use App\Enums\TalkStatus;
use App\Filament\Resources\TalkResource;
use App\Models\Conference;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TalksRelationManager extends RelationManager
{
    protected static string $relationship = 'talks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        /** @var Conference $conference */
        $conference = $this->getOwnerRecord();

        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('speaker.full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('talkCategory.name')
                    ->label(__('Category'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->suffix(' min')
                    ->icon('heroicon-o-clock')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->tooltip(fn(Talk $record) => $record->type->getDescription())
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_time')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\DateTimePicker::make('date_time')
                            ->seconds(false)
                            ->minDate($conference->start_date)
                            ->maxDate($conference->end_date)
                            ->required(),
                    ])
                    ->disabled(fn() => !$this->isEditable())
                    ->recordSelectOptionsQuery(fn(Builder $query) => $query->whereStatus(TalkStatus::Accepted)),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DetachAction::make()->requiresConfirmation()
                        ->disabled(fn() => !$this->isEditable()),
                    Tables\Actions\ViewAction::make(),
                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()->requiresConfirmation(),
                ]),
            ]);
    }


    public function infolist(Infolist $infolist): Infolist
    {
        return TalkResource::infolist($infolist);
    }

    public function isEditable(): bool
    {
        /** @var \App\Models\Conference $conference */
        $conference = $this->getOwnerRecord();
        return $conference->status === ConferenceStatus::Draft;
    }

}
