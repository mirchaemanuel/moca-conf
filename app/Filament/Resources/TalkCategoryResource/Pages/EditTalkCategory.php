<?php

namespace App\Filament\Resources\TalkCategoryResource\Pages;

use App\Filament\Resources\TalkCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTalkCategory extends EditRecord
{
    protected static string $resource = TalkCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
