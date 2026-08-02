<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Modules\Vendor\Models\Vendor;
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

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static UnitEnum|string|null $navigationGroup = 'Đối Tác & Vendor';

    protected static ?string $modelLabel = 'Vendor / Nhà Cung Cấp';

    protected static ?string $pluralModelLabel = 'Danh Mục Vendor Master';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên Nhà Cung Cấp / Studio')
                    ->required()
                    ->maxLength(255),

                Select::make('category')
                    ->label('Phân Loại Hạng Mục')
                    ->options([
                        'venue' => '🏛️ Sảnh Tiệc & Nhà Hàng',
                        'photography' => '📸 Chụp Ảnh & Quay Phim',
                        'bridal' => '👗 Váy Cưới & Trang Phục',
                        'decor' => '💐 Trang Trí Decor & Hoa Tươi',
                        'makeup' => '💄 Trang Điểm & Làm Tóc',
                        'entertainment' => '🎤 MC & Ban Nhạc Tiệc',
                        'other' => '✨ Khác',
                    ])
                    ->default('venue')
                    ->required(),

                Select::make('vibe_category')
                    ->label('Phong Cách Chủ Đạo (Vibe Style)')
                    ->options([
                        'pastel' => 'Pastel Romantic & Minimalist',
                        'royal' => 'Luxury Royal Ballroom',
                        'garden' => 'Garden Outdoor / Glasshouse',
                        'vintage' => 'Vintage Retro Film',
                        'traditional' => 'Truyền Thống Á Đông',
                    ])
                    ->default('pastel')
                    ->required(),

                TextInput::make('city')
                    ->label('Tỉnh / Thành Phố')
                    ->default('TP. Hồ Chí Minh')
                    ->maxLength(255),

                TextInput::make('district')
                    ->label('Quận / Huyện')
                    ->default('Quận 1')
                    ->maxLength(255),

                TextInput::make('latitude')
                    ->label('Tọa Độ Latitude (Bản Đồ)')
                    ->numeric()
                    ->placeholder('10.776889'),

                TextInput::make('longitude')
                    ->label('Tọa Độ Longitude (Bản Đồ)')
                    ->numeric()
                    ->placeholder('106.700806'),

                Select::make('price_tier')
                    ->label('Phân Cấp Giá')
                    ->options([
                        'budget' => 'Tiết kiệm (< 50 triệu)',
                        'standard' => 'Tiêu chuẩn (50 - 150 triệu)',
                        'premium' => 'Cao cấp (150 - 350 triệu)',
                        'luxury' => 'Luxury (> 350 triệu)',
                    ])
                    ->default('standard'),

                TextInput::make('rating')
                    ->label('Đánh Giá Sao (1.0 - 5.0)')
                    ->numeric()
                    ->default(4.9),

                TextInput::make('contact_name')
                    ->label('Người Liên Hệ')
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Số Điện Thoại')
                    ->tel()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email Liên Hệ')
                    ->email()
                    ->maxLength(255),

                TextInput::make('contract_amount')
                    ->label('Giá Hợp Đồng Dự Kiến (VND)')
                    ->numeric()
                    ->default(0),

                Textarea::make('notes')
                    ->label('Ghi Chú Nổi Bật')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên Vendor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Hạng Mục')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'venue' => 'primary',
                        'photography' => 'success',
                        'bridal' => 'warning',
                        'decor' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('vibe_category')
                    ->label('Phong Cách')
                    ->sortable(),

                TextColumn::make('city')
                    ->label('Thành Phố'),

                TextColumn::make('rating')
                    ->label('Đánh Giá')
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Điện Thoại'),

                TextColumn::make('contract_amount')
                    ->label('Giá Hợp Đồng')
                    ->money('VND')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'venue' => 'Sảnh Tiệc',
                        'photography' => 'Chụp Ảnh',
                        'bridal' => 'Váy Cưới',
                        'decor' => 'Trang Trí',
                    ]),
                SelectFilter::make('vibe_category')
                    ->options([
                        'pastel' => 'Pastel',
                        'royal' => 'Royal',
                        'garden' => 'Garden',
                        'vintage' => 'Vintage',
                    ]),
            ]);
    }
}
