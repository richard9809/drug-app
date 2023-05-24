<?php

namespace App\Filament\Resources\DispensaryResource\Pages;

use App\Filament\Resources\DispensaryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDispensary extends EditRecord
{
    protected static string $resource = DispensaryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
