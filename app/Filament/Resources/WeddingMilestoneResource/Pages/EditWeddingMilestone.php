<?php

declare(strict_types=1);

namespace App\Filament\Resources\WeddingMilestoneResource\Pages;

use App\Filament\Resources\WeddingMilestoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWeddingMilestone extends EditRecord
{
    protected static string $resource = WeddingMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
