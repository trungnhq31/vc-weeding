<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Models\Wish;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class WishResource extends Resource
{
    protected static ?string $model = Wish::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static UnitEnum|string|null $navigationGroup = 'Quản lý Thiệp Cưới';

    protected static ?string $modelLabel = 'Lời Chúc';

    protected static ?string $pluralModelLabel = 'Sổ Lời Chúc Realtime';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sender_name')
                    ->label('Người Gửi')
                    ->required()
                    ->maxLength(255),
                Select::make('guest_id')
                    ->label('Khách Mời Tương Ứng')
                    ->relationship('guest', 'name')
                    ->searchable()
                    ->nullable(),
                Textarea::make('message')
                    ->label('Nội Dung Lời Chúc')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_approved')
                    ->label('Đã Duyệt (Hiển thị công khai)')
                    ->default(true),
                Toggle::make('is_pinned')
                    ->label('Ghim Lời Chúc Hay')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender_name')
                    ->label('Người Gửi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('message')
                    ->label('Lời Chúc')
                    ->limit(60)
                    ->searchable(),
                IconColumn::make('is_approved')
                    ->label('Đã Duyệt')
                    ->boolean(),
                IconColumn::make('is_pinned')
                    ->label('Ghim')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Thời Gian Gửi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => WishResource\Pages\ListWishes::route('/'),
            'create' => WishResource\Pages\CreateWish::route('/create'),
            'edit' => WishResource\Pages\EditWish::route('/{record}/edit'),
        ];
    }
}
