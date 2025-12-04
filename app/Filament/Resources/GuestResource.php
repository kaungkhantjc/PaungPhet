<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestResource\Pages;
use App\Models\Guest;
use BackedEnum;
use Closure;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GuestResource extends Resource
{
    protected static ?string $model = Guest::class;

    protected static ?string $slug = 'guests';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Checkbox::make('is_notable')
                    ->label(__('filament/admin/guest_resource.is_notable'))
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label(__('filament/admin/guest_resource.name'))
                    ->required()
                    ->live(debounce: 500)
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->required()
                    ->label(__('filament/admin/guest_resource.slug'))
                    ->unique(table: 'guests', column: 'slug', ignoreRecord: true, modifyRuleUsing: fn(Rule $rule, Closure $get) => $rule->where('wedding_id', $get('wedding_id')))
                    ->prefix(config('app.url') . '/' . auth()->user()->wedding->slug . '/invite/')
                    ->placeholder('uncle-hla'),

                Textarea::make('note')
                    ->visibleOn('edit')
                    ->label(__('filament/admin/guest_resource.note'))
                    ->rows(2)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('filament/admin/guest_resource.user.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('wedding_id')
                    ->label(__('filament/admin/guest_resource.wedding_id')),

                TextColumn::make('name')
                    ->label(__('filament/admin/guest_resource.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(__('filament/admin/guest_resource.slug'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('filament/admin/guest_resource.status')),

                TextColumn::make('is_notable')
                    ->label(__('filament/admin/guest_resource.is_notable')),

                TextColumn::make('note')
                    ->label(__('filament/admin/guest_resource.note')),
            ])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuests::route('/'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'status' => $record->status,
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/admin/guest_resource.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/admin/guest_resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/admin/guest_resource.plural_model_label');
    }


}
