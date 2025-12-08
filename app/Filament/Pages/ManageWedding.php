<?php

namespace App\Filament\Pages;

use App\Models\Wedding;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Tapp\FilamentSocialShare\Actions\SocialShareAction;

/**
 * @property Schema $form
 */
class ManageWedding extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.manage-wedding';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;
    protected static ?string $slug = 'my-wedding';

    public function getHeading(): string|Htmlable|null
    {
        return __('filament/admin/manage_wedding.title');
    }

    /**
     * @return string
     */
    public static function getNavigationLabel(): string
    {
        return __('filament/admin/manage_wedding.title');
    }

    public ?array $data = [];

    public function mount(): void
    {
        $wedding = auth()->user()->wedding;

        if ($wedding) {
            $data = $wedding->attributesToArray();

            foreach ($wedding->getTranslatableAttributes() as $field) {
                $data[$field] = $wedding->getTranslations($field);
            }

            $this->form->fill($data);
        } else {
            $this->form->fill([
                'event_date' => now(),
                'event_time' => [
                    'my' => __('filament/admin/manage_wedding.event_time_default', locale: 'my'),
                    'my_PAO' => __('filament/admin/manage_wedding.event_time_default', locale: 'my_PAO'),
                    'my_SHN' => __('filament/admin/manage_wedding.event_time_default', locale: 'my_SHN'),
                    'en' => __('filament/admin/manage_wedding.event_time_default', locale: 'en'),
                ],
                'address' => [
                    'my' => __('filament/admin/manage_wedding.address_default', locale: 'my'),
                    'my_PAO' => __('filament/admin/manage_wedding.address_default', locale: 'my_PAO'),
                    'my_SHN' => __('filament/admin/manage_wedding.address_default', locale: 'my_SHN'),
                    'en' => __('filament/admin/manage_wedding.address_default', locale: 'en'),
                ],
                'content' => [
                    'my' => __('filament/admin/manage_wedding.content_default', locale: 'my'),
                    'my_PAO' => __('filament/admin/manage_wedding.content_default', locale: 'my_PAO'),
                    'my_SHN' => __('filament/admin/manage_wedding.content_default', locale: 'my_SHN'),
                    'en' => __('filament/admin/manage_wedding.content_default', locale: 'en'),
                ],
            ]);
        }
    }

    private function createShareAction(string $label, string $locale): SocialShareAction
    {
        return SocialShareAction::make($label)
            ->nativeBrowserShare()
            ->label($label)
            ->tooltip(null)
            ->text(__('filament/admin/guest_resource.share_wedding_url_title'))
            ->urlToShare(fn() => route('guests.show', ['locale' => $locale, 'weddingSlug' => $this->form->getState()['slug'] ?? '']));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->model(Wedding::class)
            ->columns(1)
            ->schema([
                Section::make(__('filament/admin/manage_wedding.wedding_details'))
                    ->columns()
                    ->schema([
                        Flex::make([
                            TextInput::make('slug')
                                ->required()
                                ->unique(table: 'weddings', column: 'slug', ignorable: fn() => auth()->user()->wedding)
                                ->hint(config('app.url') . '/' . app()->getLocale() . '/')
                                ->placeholder('mg-and-may')
                                ->grow(),
                            ActionGroup::make([
                                self::createShareAction(label: __('filament/admin/guest_resource.share_en_url'), locale: 'en'),
                                self::createShareAction(label: __('filament/admin/guest_resource.share_my_url'), locale: 'my'),
                                self::createShareAction(label: __('filament/admin/guest_resource.share_my_PAO_url'), locale: 'my_PAO'),
                                self::createShareAction(label: __('filament/admin/guest_resource.share_my_SHN_url'), locale: 'my_SHN'),
                            ])->icon(Heroicon::OutlinedShare)
                                ->hiddenLabel(),
                        ])->columnSpanFull()->verticalAlignment(VerticalAlignment::Start),

                        DatePicker::make('event_date')
                            ->label(__('filament/admin/manage_wedding.event_date'))
                            ->live()
                            ->hint(fn(Get $get) => Carbon::parse($get('event_date'))->translatedFormat(__('filament/admin/manage_wedding.event_date_format')))
                            ->required(),
                        TextInput::make('address_url')
                            ->label(__('filament/admin/manage_wedding.address_url'))
                            ->placeholder('https://maps.app.goo.gl/...'),

                        FileUpload::make('og_image_path')
                            ->label(__('filament/admin/manage_wedding.og_image'))
                            ->directory('weddings/' . auth()->id() . '/og-images')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->required()
                            ->imageCropAspectRatio('16:9')
                            ->maxSize(5 * 1024 * 1024)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg']),

                        FileUpload::make('bg_image_path')
                            ->label(__('filament/admin/manage_wedding.bg_image'))
                            ->directory('weddings/' . auth()->id() . '/bg-images')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->required()
                            ->maxSize(5 * 1024 * 1024)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg'])
                    ])
                    ->columnSpanFull(),

                $this->createPartnerSection("မြန်မာစာဖြင့် ဖိတ်ကြားရန်", 'my'),
                $this->createPartnerSection("ပအိုဝ်းစာဖြင့် ဖိတ်ကြားရန်", 'my_PAO'),
                $this->createPartnerSection("ရှမ်းစာဖြင့် ဖိတ်ကြားရန်", 'my_SHN'),
                $this->createPartnerSection("Invitation in English", 'en'),
            ]);
    }

    private function createPartnerSection(string $description, string $locale): Section
    {
        return Section::make(__('filament/admin/manage_wedding.partners', locale: $locale))
            ->description($description)
            ->columns()
            ->schema([
                TextInput::make("partner_one.$locale")
                    ->required()
                    ->label(__('filament/admin/manage_wedding.partner_one', locale: $locale))
                    ->placeholder(__('filament/admin/manage_wedding.partner_one_placeholder', locale: $locale)),
                TextInput::make("partner_two.$locale")
                    ->required()
                    ->label(__('filament/admin/manage_wedding.partner_two', locale: $locale))
                    ->placeholder(__('filament/admin/manage_wedding.partner_two_placeholder', locale: $locale)),

                RichEditor::make("content.$locale")
                    ->label(__('filament/admin/manage_wedding.content', locale: $locale))
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('weddings/' . auth()->id() . '/contents')
                    ->fileAttachmentsVisibility('public')
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                        ['h1', 'h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                        ['textColor', 'table', 'grid', 'attachFiles'],
                        ['undo', 'redo'],
                    ])
                    ->columnSpanFull(),

                TextInput::make("event_time.$locale")
                    ->label(__('filament/admin/manage_wedding.event_time', locale: $locale))
                    ->columnSpanFull(),
                Textarea::make("address.$locale")
                    ->label(__('filament/admin/manage_wedding.address', locale: $locale))
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament/admin/manage_wedding.save'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = auth()->user();

        $user->wedding()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        Notification::make()
            ->success()
            ->title(__('filament/admin/manage_wedding.wedding_details_saved_successfully'))
            ->send();
    }

    public function getTitle(): string
    {
        return __('filament/admin/manage_wedding.title');
    }


}
