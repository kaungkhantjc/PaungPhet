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
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Tapp\FilamentSocialShare\Actions\SocialShareAction;

class GuestResource extends Resource
{
    protected static ?string $model = Guest::class;

    protected static ?string $slug = 'guests';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;
    protected static ?int $navigationSort = 3;

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
                    ->color(fn(Model $record) => $record->status == 'seen' ? Color::Green : Color::Gray)
                    ->toggleable()
                    ->sortable(),

                IconColumn::make('is_notable')
                    ->label(__('filament/admin/guest_resource.is_notable'))
                    ->boolean()
                    ->alignCenter()
                    ->toggleable()
                    ->sortable(),

                IconColumn::make('note')
                    ->label(__('filament/admin/guest_resource.note'))
                    ->boolean()
                    ->state(fn(Model $record) => Str::of($record->note)->trim()->isNotEmpty())
                    ->trueIcon(Heroicon::OutlinedEnvelope)
                    ->falseIcon(Heroicon::OutlinedMinusCircle)
                    ->falseColor(Color::Gray)
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
                SelectFilter::make('status')
                    ->options([
                        'pending' => __('filament/admin/guest_resource.status_pending'),
                        'seen' => __('filament/admin/guest_resource.status_seen'),
                    ])
                    ->label(__('filament/admin/guest_resource.status'))
                    ->placeholder(__('filament/admin/panel.all')),

                TernaryFilter::make('is_notable')
                    ->label(__('filament/admin/guest_resource.is_notable'))
                    ->trueLabel(__('filament/admin/guest_resource.is_notable_yes'))
                    ->falseLabel(__('filament/admin/guest_resource.is_notable_no')),

                Filter::make('has_note')
                    ->label(__('filament/admin/guest_resource.has_note'))
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('note')),
            ])
            ->recordActions([
                ActionGroup::make([
                    self::createShareAction(label: __('filament/admin/guest_resource.share_en_url'), locale: 'en'),
                    self::createShareAction(label: __('filament/admin/guest_resource.share_my_url'), locale: 'my'),
                    self::createShareAction(label: __('filament/admin/guest_resource.share_my_BLK_url'), locale: 'my_BLK'),
                ])->icon(Heroicon::OutlinedShare),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ], RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function createShareAction(string $label, string $locale): SocialShareAction
    {
        return SocialShareAction::make($label)
            ->nativeBrowserShare()
            ->label($label)
            ->tooltip(null)
            ->text(fn(Model $record) => __('filament/admin/guest_resource.share_invitation_title', ['name' => $record->name], locale: $locale))
            ->urlToShare(fn(Model $record) => route('guests.invite', ['locale' => $locale, 'weddingSlug' => auth()->user()->wedding->slug, 'guestSlug' => $record->slug]));
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
