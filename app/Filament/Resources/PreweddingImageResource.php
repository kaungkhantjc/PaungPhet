<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreweddingImageResource\Pages;
use App\Models\PreweddingImage;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PreweddingImageResource extends Resource
{
    protected static ?string $model = PreweddingImage::class;

    protected static ?string $slug = 'prewedding-images';
    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('filament/admin/prewedding_image_resource.name'))
                    ->required(),
                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                FileUpload::make('path')
                    ->label('Image')
                    ->directory('weddings/' . auth()->id() . '/prewedding-images')
                    ->disk('public')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->maxSize(5 * 1024 * 1024)
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg'])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label(__('filament/admin/prewedding_image_resource.order'))
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label(__('filament/admin/prewedding_image_resource.name'))
                    ->sortable()
                    ->toggleable(),

                ImageColumn::make('path')
                    ->label(__('filament/admin/prewedding_image_resource.image'))
                    ->disk('public')
                    ->url(fn(PreweddingImage $record): string => $record->url)
                    ->openUrlInNewTab(),

                TextColumn::make('updated_at')
                    ->label(__('filament/admin/panel.updated_at'))
                    ->since()
                    ->dateTimeTooltip('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/admin/panel.created_at'))
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('sort_order', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('wedding_id', auth()->user()->wedding?->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreweddingImages::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/admin/prewedding_image_resource.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/admin/prewedding_image_resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/admin/prewedding_image_resource.plural_model_label');
    }

}
