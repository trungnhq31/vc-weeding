<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\WeddingMilestoneResource\Pages;
use App\Models\WeddingMilestone;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class WeddingMilestoneResource extends Resource
{
    protected static ?string $model = WeddingMilestone::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static UnitEnum|string|null $navigationGroup = 'Kế Hoạch Tiệc Cưới';

    protected static ?string $navigationLabel = 'Lộ Trình & Tiến Độ';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông Tin Giai Đoạn')
                    ->schema([
                        TextInput::make('title')
                            ->label('Tên Giai Đoạn')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('timeframe')
                            ->label('Mốc Thời Gian (VD: 15/07 - 31/07/2026)')
                            ->required(),
                        Select::make('status')
                            ->label('Trạng Thái')
                            ->options([
                                'completed' => 'Đã Hoàn Thành',
                                'in_progress' => 'Đang Thực Hiện',
                                'pending' => 'Chờ Chuẩn Bị',
                            ])
                            ->default('in_progress')
                            ->required(),
                        TextInput::make('order')
                            ->label('Thứ Tự Hiển Thị')
                            ->numeric()
                            ->default(1),
                        TextInput::make('budget_allocated')
                            ->label('Ngân Sách Dự Kiến (VNĐ)')
                            ->numeric()
                            ->prefix('₫')
                            ->default(0),
                        TextInput::make('budget_spent')
                            ->label('Chi Phí Thực Tế (VNĐ)')
                            ->numeric()
                            ->prefix('₫')
                            ->default(0),
                        Textarea::make('summary')
                            ->label('Tóm Tắt Giai Đoạn')
                            ->rows(2)
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Ghi Chú & Nhật Ký Giai Đoạn')
                            ->rows(3)
                            ->columnSpanFull(),
                        FileUpload::make('attachments')
                            ->label('Hình Ảnh & Tài Liệu Giai Đoạn')
                            ->multiple()
                            ->image()
                            ->disk('public')
                            ->directory('wedding/attachments')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Danh Sách Công Việc (Checklist)')
                    ->schema([
                        Repeater::make('tasks')
                            ->relationship()
                            ->schema([
                                TextInput::make('title')
                                    ->label('Tên Công Việc')
                                    ->required()
                                    ->columnSpan(2),
                                Toggle::make('is_completed')
                                    ->label('Đã Xong')
                                    ->default(false),
                                TextInput::make('estimated_cost')
                                    ->label('Chi Phí Dự Kiến')
                                    ->numeric()
                                    ->prefix('₫'),
                                TextInput::make('actual_cost')
                                    ->label('Chi Phí Thực Tế')
                                    ->numeric()
                                    ->prefix('₫'),
                                TextInput::make('vendor_info')
                                    ->label('Nhà Cung Cấp / Đơn Vị Liên Hệ')
                                    ->columnSpan(2),
                                Textarea::make('notes')
                                    ->label('Ghi Chú Chi Tiết')
                                    ->rows(2)
                                    ->columnSpan(3),
                                FileUpload::make('attachments')
                                    ->label('Ảnh Hợp Đồng / Chứng Từ / Thiết Kế')
                                    ->multiple()
                                    ->image()
                                    ->disk('public')
                                    ->directory('wedding/attachments')
                                    ->columnSpanFull(),
                            ])
                            ->columns(5)
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Tên Giai Đoạn')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('timeframe')
                    ->label('Mốc Thời Gian'),
                Tables\Columns\TextColumn::make('budget_allocated')
                    ->label('Dự Kiến')
                    ->money('VND'),
                Tables\Columns\TextColumn::make('budget_spent')
                    ->label('Đã Chi')
                    ->money('VND'),
            ])
            ->defaultSort('order')
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWeddingMilestones::route('/'),
            'create' => Pages\CreateWeddingMilestone::route('/create'),
            'edit' => Pages\EditWeddingMilestone::route('/{record}/edit'),
        ];
    }
}
