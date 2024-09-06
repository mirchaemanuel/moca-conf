<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeakerResource\Pages;
use App\Models\Speaker;
use Countries;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Str;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([

                        Forms\Components\Tabs\Tab::make(__('General Information'))
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                Split::make([
                                    Section::make([
                                        Forms\Components\TextInput::make('first_name')
                                            ->required(),
                                        Forms\Components\TextInput::make('last_name')
                                            ->required(),
                                        Forms\Components\TextInput::make('nickname'),
                                        Forms\Components\Select::make('country')
                                            ->placeholder(__('Select a country'))
                                            ->searchable()
                                            ->options(
                                                Countries::getList(app()->getLocale())
                                            ),
                                    ])->columns(2),
                                    Section::make([
                                        FileUpload::make('avatar')
                                            ->avatar()
                                            ->directory('avatars')
                                            ->imageEditor()
                                            ->maxSize(1024 * 1024 * 10)
                                            ->columnSpanFull(),
                                    ])->grow(false),
                                ])
                                    ->from('md')
                                    ->columnSpanFull(),

                                Forms\Components\MarkdownEditor::make('bio')
                                    ->disableToolbarButtons([
                                        'attachFiles',
                                    ])
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\Tabs\Tab::make(__('Job Information'))
                            ->icon('heroicon-s-briefcase')
                            ->schema([
                                Forms\Components\TextInput::make('company'),
                                Forms\Components\TextInput::make('job_title'),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('Contacts'))
                            ->icon('heroicon-o-device-phone-mobile')
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->email(),
                                Forms\Components\TextInput::make('phone')
                                    ->tel(),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('Social'))
                            ->icon('bi-link-45deg')
                            ->schema([
                                Forms\Components\TextInput::make('linkedin')
                                    ->prefixicon('bi-linkedin')
                                    ->url(),
                                Forms\Components\TextInput::make('twitter')
                                    ->prefixicon('bi-twitter-x')
                                    ->url(),
                                Forms\Components\TextInput::make('facebook')
                                    ->prefixicon('bi-facebook')
                                    ->url(),
                                Forms\Components\TextInput::make('instagram')
                                    ->prefixicon('bi-instagram')
                                    ->url(),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('Internal Information'))
                            ->icon('heroicon-o-ellipsis-horizontal')
                            ->schema([
                                Forms\Components\Textarea::make('notes')
                                    ->columnSpanFull(),
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->height(120)
                    ->circular(),
                Tables\Columns\TextColumn::make('full_name')
                    ->description(fn(Speaker $record): string => $record->nickname ?? '')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->icon(fn(Speaker $record) => $record->country === null ? '' : 'flag-country-' . Str::lower($record->country))
                    ->iconPosition(IconPosition::After)
                    ->tooltip(fn(Speaker $record) => $record->country === null ? '' : Countries::getOne($record->country))
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->copyMessage(__('Copied to clipboard'))
                    ->copyMessageDuration(1000)
                    ->toggleable()
                    ->fontFamily(FontFamily::Mono)
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('company')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_title')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('acceptedTalks.title')
                    ->translateLabel()
                    ->bulleted()
                    ->weight('medium')
                    ->size(TextColumnSize::ExtraSmall)
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->toggleable(),
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
                SelectFilter::make('country')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(
                        Countries::getList(app()->getLocale())
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

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
            'index'  => Pages\ListSpeakers::route('/'),
            'create' => Pages\CreateSpeaker::route('/create'),
            'edit'   => Pages\EditSpeaker::route('/{record}/edit'),
        ];
    }
}
