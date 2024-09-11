<?php

namespace App\Filament\Resources;

use App\Enums\TalkStatus;
use App\Enums\TalkType;
use App\Filament\Resources\TalkResource\Pages;
use App\Models\Speaker;
use App\Models\Talk;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Component as InfolistsComponent;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('speaker.fullName')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('talkCategory.name')
                    ->label(__('Category'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable()
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
                Tables\Filters\SelectFilter::make('speaker')
                    ->label(__('Speaker'))
                    ->relationship('speaker', 'id')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn(Speaker $speaker): string => $speaker->full_name_with_nick),
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
                Tables\Filters\SelectFilter::make('talk_category')
                    ->label(__('Category'))
                    ->relationship('talkCategory', 'name')
                    ->searchable()
                    ->preload(),

            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),

                    // accept a submitted talk
                    Tables\Actions\Action::make('Accept')
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->visible(fn($record) => $record->status === TalkStatus::Submitted)
                        ->action(fn($record) => $record->update(['status' => TalkStatus::Accepted])),

                    // reject a submitted talk
                    Tables\Actions\Action::make('Reject')
                        ->requiresConfirmation()
                        ->color('warning')
                        ->icon('heroicon-o-x-circle')
                        ->visible(fn($record) => $record->status === TalkStatus::Submitted)
                        ->action(fn($record) => $record->update(['status' => TalkStatus::Rejected])),

                    // cancel a submitted talk
                    Tables\Actions\Action::make('Cancel')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('heroicon-o-trash')
                        ->visible(fn($record) => $record->status === TalkStatus::Submitted)
                        ->action(fn($record) => $record->update(['status' => TalkStatus::Cancelled])),

                    // restore to submitted a cancelled/rejected/accepted talk
                    Tables\Actions\Action::make(__('Restore to Submitted'))
                        ->requiresConfirmation()
                        ->color('primary')
                        ->icon('heroicon-o-arrow-path')
                        ->visible(fn($record) => $record->status !== TalkStatus::Submitted)
                        ->action(fn($record) => $record->update(['status' => TalkStatus::Submitted])),

                ])

            ])
            ->recordUrl(fn(Talk $record) => route('filament.admin.resources.talks.edit', compact('record')))
            ->bulkActions([
                Tables\Actions\BulkAction::make(__('Accept all'))
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->action(fn(Collection $records) => $records->each->update(['status' => TalkStatus::Accepted])),
                Tables\Actions\BulkAction::make(__('Reject all'))
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->action(fn(Collection $records) => $records->each->update(['status' => TalkStatus::Rejected])),
            ])
            ->selectCurrentPageOnly()
            ->checkIfRecordIsSelectableUsing(
                fn(Talk $record): bool => $record->status === TalkStatus::Submitted,
            );
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('Speaker Information'))
                    ->columns(2)
                    ->schema([
                        ImageEntry::make('speaker.avatar')
                            ->label('')
                            ->height(120)
                            ->circular(),
                        Group::make()
                            ->schema([
                                TextEntry::make('speaker.full_name_with_nick')
                                    ->label(__('Speaker')),
                                TextEntry::make('speaker.email')
                                    ->label(__('Email')),
                                TextEntry::make('speaker.phone')
                                    ->label(__('Phone')),
                                TextEntry::make('speaker.country')
                                    ->label(__('Country')),
                            ])->columns(2),
                        Fieldset::make(__('Biography'))
                            ->schema([
                                TextEntry::make('speaker.bio')
                                    ->label('')
                                    ->markdown()
                                    ->prose()
                                    ->columnSpanFull(),
                            ])->columnSpanFull(),
                    ]),
                self::talkInformationInfolistSection(),
            ]);
    }

    public static function talkInformationInfolistSection(): InfolistsComponent
    {
        return
            Section::make(__('Talk Information'))
                ->columns(3)
                ->schema([
                    TextEntry::make('title')
                        ->label(__('Title'))
                        ->columnSpan(2),
                    TextEntry::make('status')
                        ->translateLabel(),
                    TextEntry::make('talkCategory.name')
                        ->label(__('Category')),
                    TextEntry::make('type')
                        ->translateLabel(),
                    TextEntry::make('duration')
                        ->icon('heroicon-o-clock')
                        ->translateLabel()
                        ->suffix(' min'),
                    TextEntry::make('abstract')
                        ->translateLabel()
                        ->columnSpanFull(),
                    Fieldset::make(__('Description'))
                        ->schema([
                            TextEntry::make('description')
                                ->label('')
                                ->markdown()
                                ->prose()
                                ->columnSpanFull(),
                        ])->columnSpanFull(),
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
            'view'   => Pages\ViewTalk::route('/{record}'),
        ];
    }

    /**
     * @return array<Forms\Components\Component>|Closure
     */
    public static function getForm(?Speaker $speaker = null): array|Closure
    {
        return [
            Forms\Components\Section::make(__('Base Information'))
                ->icon('heroicon-o-user-circle')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('speaker_id')
                        ->relationship('speaker', 'id')
                        ->searchable()
                        ->preload()
                        ->getOptionLabelFromRecordUsing(fn(Speaker $speaker): string => "{$speaker->first_name} {$speaker->last_name} ({$speaker->nickname})")
                        ->required()
                        ->hidden($speaker !== null),
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
                        ->prefixIcon('heroicon-o-clock')
                        ->suffix(' min')
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


        ];
    }
}
