<?php

declare(strict_types=1);

namespace App\Filament\Resources\WeddingMilestoneResource\Pages;

use App\Filament\Resources\WeddingMilestoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWeddingMilestones extends ListRecords
{
    protected static string $resource = WeddingMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
