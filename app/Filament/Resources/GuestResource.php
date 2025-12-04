<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuestResource\Pages;
use App\Models\Guest;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

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
                TextInput::make('name')
                    ->label(__('filament/admin/guest_resource.name'))
                    ->required()
                    ->visibleOn(['create', 'edit'])
                    ->live(debounce: 500)
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->required()
                    ->visibleOn(['create', 'edit'])
                    ->unique(table: 'guests', column: 'slug', ignoreRecord: true, modifyRuleUsing: fn(Unique $rule) => $rule->where('wedding_id', auth()->user()->wedding->id))
                    ->prefix(config('app.url') . '/' . auth()->user()->wedding->slug . '/invite/'),

                TextEntry::make('status')
                    ->label(__('filament/admin/guest_resource.status'))
                    ->visibleOn('view')
                    ->badge()
                    ->state(fn(Model $record) => $record->status == 'seen' ? __('filament/admin/guest_resource.status_seen') : __('filament/admin/guest_resource.status_pending')),

                Checkbox::make('is_notable')
                    ->label(__('filament/admin/guest_resource.is_notable'))
                    ->columnSpanFull()
                    ->default(true),

                TextEntry::make('note')
                    ->visibleOn(['view'])
                    ->default('~')
                    ->label(__('filament/admin/guest_resource.note'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament/admin/guest_resource.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label(__('filament/admin/guest_resource.status'))
                    ->badge()
                    ->state(fn(Model $record) => $record->status == 'seen' ? __('filament/admin/guest_resource.status_seen') : __('filament/admin/guest_resource.status_pending'))
                    ->toggleable(),

                IconColumn::make('is_notable')
                    ->label(__('filament/admin/guest_resource.is_notable'))
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label(__('filament/admin/panel.updated_at'))
                    ->dateTime()
                    ->dateTimeTooltip('M j, Y g:i A')
                    ->since()
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament/admin/panel.created_at'))
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('wedding_id', auth()->user()->wedding->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuests::route('/'),
        ];
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
            __('filament/admin/guest_resource.status') => $record->status == 'seen' ? __('filament/admin/guest_resource.status_seen') : __('filament/admin/guest_resource.status_pending'),
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
