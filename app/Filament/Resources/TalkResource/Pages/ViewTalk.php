<?php

namespace App\Filament\Resources\TalkResource\Pages;

use App\Filament\Resources\TalkResource;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTalk extends ViewRecord
{
    protected static string $resource = TalkResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
