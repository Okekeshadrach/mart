<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * @property-read Schema $content
 * @property-read Schema $form
 */

class SiteSettings extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static \UnitEnum|string|null $navigationGroup = 'Storefront';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    protected ?SiteSetting $record = null;

    public function mount(): void
    {
        $this->record = SiteSetting::current();

        $this->form->fill($this->getFillData());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->model($this->record)
            ->statePath('data')
            ->schema([
                Section::make('Branding')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('site_name')
                                    ->label('Site name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('site_tagline')
                                    ->label('Site tagline')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Textarea::make('meta_description')
                            ->label('Default meta description')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Support & Contact')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('support_email')
                                    ->label('Support email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('support_phone')
                                    ->label('Support phone')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Textarea::make('support_address')
                            ->label('Support address')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('contact_title')
                                    ->label('Contact page title')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('contact_form_success_message')
                                    ->label('Contact form success message')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Textarea::make('contact_description')
                            ->label('Contact page description')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('About Page')
                    ->schema([
                        TextInput::make('about_title')
                            ->label('About page title')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('about_description')
                            ->label('About page description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        Repeater::make('about_features')
                            ->label('About feature cards')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('description')
                                    ->required()
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->minItems(3)
                            ->maxItems(3)
                            ->reorderable(false)
                            ->columnSpanFull(),
                        Repeater::make('about_stats')
                            ->label('Public stats')
                            ->schema([
                                TextInput::make('value')
                                    ->required()
                                    ->maxLength(50),
                                TextInput::make('label')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->minItems(4)
                            ->maxItems(4)
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),
                Section::make('Storefront Labels')
                    ->schema([
                        TextInput::make('shipping_summary_label')
                            ->label('Cart / checkout shipping label')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make([
                    $this->getSaveAction(),
                ])
                    ->fullWidth()
                    ->key('form-actions'),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record ??= SiteSetting::current();
        $this->record->fill($this->mutateDataBeforeSave($data));
        $this->record->save();

        Notification::make()
            ->success()
            ->title('Site settings saved.')
            ->send();
    }

    protected function getSaveAction(): Action
    {
        return Action::make('save')
            ->label('Save settings')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getFillData(): array
    {
        $data = $this->record?->toArray() ?? [];
        $defaults = SiteSetting::defaultAttributes();

        return array_merge($defaults, $data, [
            'about_features' => $data['about_features'] ?? $defaults['about_features'],
            'about_stats' => $data['about_stats'] ?? $defaults['about_stats'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateDataBeforeSave(array $data): array
    {
        $data['about_features'] = collect($data['about_features'] ?? [])
            ->map(fn (array $feature) => [
                'title' => trim((string) ($feature['title'] ?? '')),
                'description' => trim((string) ($feature['description'] ?? '')),
            ])
            ->values()
            ->all();

        $data['about_stats'] = collect($data['about_stats'] ?? [])
            ->map(fn (array $stat) => [
                'value' => trim((string) ($stat['value'] ?? '')),
                'label' => trim((string) ($stat['label'] ?? '')),
            ])
            ->values()
            ->all();

        return $data;
    }
}
