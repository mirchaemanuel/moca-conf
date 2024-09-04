<?php

namespace App\Filament\Resources;

use App\Enums\TalkStatus;
use App\Enums\TalkType;
use App\Filament\Resources\TalkResource\Pages;
use App\Models\Speaker;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Base Information'))
                    ->icon('heroicon-o-user-circle')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('speaker_id')
                            ->relationship('speaker', 'id')
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn(Speaker $speaker): string => "{$speaker->first_name} {$speaker->last_name} ({$speaker->nickname})")
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->columnSpanFull()
                            ->required(),
                    ]),
                Forms\Components\Section::make(__('Details'))
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('abstract')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->disableToolbarButtons([
                                'attachFiles',
                            ])
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make(__('Characteristics'))
                    ->icon('heroicon-o-ellipsis-horizontal-circle')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('talk_category_id')
                            ->relationship('talkCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->options(TalkType::class)
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('duration')
                            ->hint('In minutes')
                            ->required()
                            ->integer()
                            ->default(30),
                    ]),
                Forms\Components\Fieldset::make(__('Status'))
                    ->schema([
                        Forms\Components\Radio::make('status')
                            ->label('')
                            ->options(
                                TalkStatus::class
                            )
                            ->required()
                            ->columnSpanFull(),
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('speaker.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('talkCategory.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->tooltip(fn(Talk $record) => $record->type->getDescription())
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('speaker_id')
                    ->relationship('speaker', 'id')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn(Speaker $speaker): string => "{$speaker->first_name} {$speaker->last_name} ({$speaker->nickname})"),
                Tables\Filters\SelectFilter::make('status')
                    ->options(TalkStatus::class)
                    ->preload()
                    ->multiple()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('type')
                    ->options(TalkType::class)
                    ->preload()
                    ->multiple()
                    ->searchable(),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
            'edit'   => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
