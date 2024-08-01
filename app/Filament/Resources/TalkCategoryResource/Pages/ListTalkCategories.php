<?php

namespace App\Filament\Resources\TalkCategoryResource\Pages;

use App\Filament\Resources\TalkCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTalkCategories extends ListRecords
{
    protected static string $resource = TalkCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
