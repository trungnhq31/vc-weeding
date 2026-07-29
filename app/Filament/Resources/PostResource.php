<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PostStatus;
use App\Models\Post;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static UnitEnum|string|null $navigationGroup = 'Personal Knowledge Hub';

    protected static ?string $modelLabel = 'Bài Viết Blog';

    protected static ?string $pluralModelLabel = 'Danh Sách Bài Viết';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Tiêu Đề Bài Viết')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('URL Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('category_id')
                    ->label('Chuyên Mục')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->nullable(),
                Select::make('status')
                    ->label('Trạng Thái')
                    ->options([
                        PostStatus::Draft->value => PostStatus::Draft->label(),
                        PostStatus::Published->value => PostStatus::Published->label(),
                        PostStatus::Archived->value => PostStatus::Archived->label(),
                    ])
                    ->default(PostStatus::Draft->value)
                    ->required(),
                Textarea::make('excerpt')
                    ->label('Tóm Tắt Ngắn')
                    ->columnSpanFull(),
                MarkdownEditor::make('content_markdown')
                    ->label('Nội Dung Markdown')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('published_at')
                    ->label('Thời Gian Xuất Bản'),
                TextInput::make('reading_time_minutes')
                    ->label('Thời Gian Đọc (Phút)')
                    ->numeric()
                    ->default(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Tiêu Đề')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Chuyên Mục')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Trạng Thái')
                    ->badge()
                    ->color(fn (PostStatus|string $state): string => match ($state instanceof PostStatus ? $state->value : $state) {
                        PostStatus::Published->value => 'success',
                        PostStatus::Archived->value => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (PostStatus|string $state) => $state instanceof PostStatus ? $state->label() : $state),
                TextColumn::make('published_at')
                    ->label('Thời Gian Xuất Bản')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => PostResource\Pages\ListPosts::route('/'),
            'create' => PostResource\Pages\CreatePost::route('/create'),
            'edit' => PostResource\Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
