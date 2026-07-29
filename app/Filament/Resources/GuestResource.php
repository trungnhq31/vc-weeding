<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\RsvpStatus;
use App\Models\Guest;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class GuestResource extends Resource
{
    protected static ?string $model = Guest::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static UnitEnum|string|null $navigationGroup = 'Quản lý Thiệp Cưới';

    protected static ?string $modelLabel = 'Khách Mời';

    protected static ?string $pluralModelLabel = 'Danh Sách Khách Mời';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên Khách Mời')
                    ->required()
                    ->maxLength(255),
                TextInput::make('guest_slug')
                    ->label('Slug Thiệp Cá Nhân')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('salutation')
                    ->label('Lời Xưng Hô')
                    ->placeholder('VD: Trân trọng kính mời Anh Tuấn & Chị Lan')
                    ->maxLength(255),
                TextInput::make('group')
                    ->label('Nhóm Khách')
                    ->placeholder('Gia đình, Đồng nghiệp, Bạn phổ thông...'),
                TextInput::make('estimated_count')
                    ->label('Số Lượng Dự Kiến')
                    ->numeric()
                    ->default(1)
                    ->required(),
                TextInput::make('confirmed_count')
                    ->label('Số Lượng Xác Nhận')
                    ->numeric()
                    ->default(0),
                Select::make('rsvp_status')
                    ->label('Trạng Thái RSVP')
                    ->options([
                        RsvpStatus::Pending->value => RsvpStatus::Pending->label(),
                        RsvpStatus::Attending->value => RsvpStatus::Attending->label(),
                        RsvpStatus::Declined->value => RsvpStatus::Declined->label(),
                    ])
                    ->default(RsvpStatus::Pending->value)
                    ->required(),
                TextInput::make('dietary_preference')
                    ->label('Ghi Chú Thực Đơn')
                    ->maxLength(255),
                Textarea::make('notes')
                    ->label('Ghi Chú Bổ Sung')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên Khách Mời')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('guest_slug')
                    ->label('Slug Thiệp')
                    ->copyable()
                    ->copyMessage('Đã sao chép slug link!')
                    ->searchable(),
                TextColumn::make('group')
                    ->label('Nhóm')
                    ->badge()
                    ->sortable(),
                TextColumn::make('confirmed_count')
                    ->label('Xác Nhận / Dự Kiến')
                    ->formatStateUsing(fn ($record) => "{$record->confirmed_count} / {$record->estimated_count} người")
                    ->sortable(),
                TextColumn::make('rsvp_status')
                    ->label('Trạng Thái')
                    ->badge()
                    ->color(fn (RsvpStatus|string $state): string => match ($state instanceof RsvpStatus ? $state->value : $state) {
                        RsvpStatus::Attending->value => 'success',
                        RsvpStatus::Declined->value => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (RsvpStatus|string $state) => $state instanceof RsvpStatus ? $state->label() : $state),
                TextColumn::make('created_at')
                    ->label('Ngày Tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('rsvp_status')
                    ->label('Lọc Trạng Thái')
                    ->options([
                        RsvpStatus::Pending->value => RsvpStatus::Pending->label(),
                        RsvpStatus::Attending->value => RsvpStatus::Attending->label(),
                        RsvpStatus::Declined->value => RsvpStatus::Declined->label(),
                    ]),
                SelectFilter::make('group')
                    ->label('Lọc Theo Nhóm'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => GuestResource\Pages\ListGuests::route('/'),
            'create' => GuestResource\Pages\CreateGuest::route('/create'),
            'edit' => GuestResource\Pages\EditGuest::route('/{record}/edit'),
        ];
    }
}
