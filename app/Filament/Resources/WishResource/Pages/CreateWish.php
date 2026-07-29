<?php

declare(strict_types=1);

namespace App\Filament\Resources\WishResource\Pages;

use App\Filament\Resources\WishResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWish extends CreateRecord
{
    protected static string $resource = WishResource::class;
}
