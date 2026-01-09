<?php

namespace Kaster\Cms\Filament\Resources\Pages;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Kaster\Cms\Enums\PageStatus;
use Kaster\Cms\Enums\PageTheme;
use Kaster\Cms\Filament\CommonFields;
use Kaster\Cms\Filament\Components\FilamentComponentsService;
use Kaster\Cms\Filament\Resources\Pages\Pages\CreatePage;
use Kaster\Cms\Filament\Resources\Pages\Pages\EditPage;
use Kaster\Cms\Filament\Resources\Pages\Pages\ListPages;
use Kaster\Cms\Filament\Resources\Pages\Pages\ViewPage;
use Kaster\Cms\Models\Page;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    // protected static ?string $modelLabel = 'Page';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';


    protected static ?string $label = null;

    //    public static function getLabel(): ?string
//    {
//        return __('filament.page');
//    }
//
//    public static function getNavigationGroup(): ?string
//    {
//        return __('filament.pages');
//    }

    public static function form(Schema $schema): Schema
    {
        $filamentComponentService = app(FilamentComponentsService::class);

        $contentTab = [
            Grid::make(4)->schema([
                Section::make('Content')
                    ->columnSpan(fn($livewire): int => (int) ($livewire->isJsonVisible ? 2 : 4))
                    ->columns(1)
                    ->schema([
                        Fieldset::make('Content')
                            ->columns(2)
                            ->schema([
                                'title' => TextInput::make('title')
                                    ->label(__('Page title'))
                                    ->live(debounce: 400)
                                    ->afterStateUpdated(function (string $operation, string $state, Set $set): void {
                                        $set('slug', Str::slug($state));
                                        $set('meta_title', $state);
                                        $set('opengraph_title', $state);
                                    })

                                    ->required(),

                                'slug' => TextInput::make('slug')
                                    ->label(__('Slug'))
                                    ->live(debounce: 400)
                                    ->afterStateUpdated(
                                        fn(string $operation, string $state, Set $set): mixed => $operation === 'create'
                                        ? $set('slug', Str::slug($state)) : null
                                    )
                                    ->required(),
                            ]),
                        $filamentComponentService->getFlexibleContentFieldsForModel(Page::class),
                    ]),
                View::make('cms::filament.preview-json') // Preview
                    ->columnSpanFull()
                    ->visible(fn($livewire): bool => $livewire->isJsonVisible)
                    ->reactive(),
            ]),
        ];

        $parametersTab = [
            'is_landing' => Select::make('is_landing')
                ->label(__('Is Landing?'))
                ->helperText(__('Used to marketing campaigns'))
                ->boolean()
                ->default(false),
            'deletable' => Select::make('deletable')
                ->label(__('Deletable'))
                ->helperText(__('Internal: used for homepage/blog'))
                ->boolean()
                ->default(true),
            'parent_id' => Select::make('parent_id')
                ->label(__('Parent Page'))
                ->placeholder(__('Select a parent page'))
                // @phpstan-ignore-next-line
                ->options(fn(?Model $record): Collection => Page::query()->get()->pluck('title', 'id'))
                ->searchable(),
            'status' => Select::make('status')
                ->label(__('Status'))
                ->placeholder(__('Select a status'))
                ->options(PageStatus::class)
                ->default(PageStatus::Published)
                ->required(),
            'theme' => Select::make('theme')
                ->enum(PageTheme::class)
                ->options(PageTheme::class)
                ->default(PageTheme::Default),
            'published_at' => DatePicker::make('published_at')
                ->label(__('Published At'))
                ->native(false)
                ->default(now())
                ->required(),
        ];

        $result = [
            'tabs' => Tabs::make('Tabs')
                ->tabs([
                    'content' => Tab::make(__('Content'))
                        ->label(__('Content'))
                        ->schema($contentTab),
                    'parameters' => Tab::make(__('Parameters'))
                        ->label(__('Parameters'))
                        ->schema($parametersTab)
                        ->columns(),
                    'seo' => Tab::make(__('SEO'))
                        ->schema([
                            Section::make('SEO & Social')
                                ->icon('heroicon-o-globe-alt')
                                ->collapsible()
                                ->collapsed()
                                ->schema([
                                    Group::make()
                                        ->statePath('seo_metadata')
                                        ->schema([
                                            Toggle::make('no_index')
                                                ->label('Hide from Search Engines (No Index)')
                                                ->helperText('If enabled, adds <meta name="robots" content="noindex">')
                                                ->default(false),

                                            TextInput::make('meta_title')
                                                ->label('Meta Title')
                                                ->placeholder(fn ($get) => $get('../title'))
                                                ->maxLength(60)
                                                ->columnSpanFull(),

                                            Textarea::make('meta_description')
                                                ->label('Meta Description')
                                                ->rows(3)
                                                ->maxLength(160)
                                                ->columnSpanFull(),

                                            Section::make('Social Sharing (OpenGraph)')
                                                ->description('Customize how this page looks when shared on Facebook/Twitter.')
                                                ->schema([
                                                    TextInput::make('og_title')
                                                        ->label('Social Title')
                                                        ->placeholder(fn ($get) => $get('meta_title') ?: $get('../title')),

                                                    Textarea::make('og_description')
                                                        ->label('Social Description')
                                                        ->placeholder(fn ($get) => $get('meta_description'))
                                                        ->rows(2),

                                                    FileUpload::make('og_image')
                                                        ->label('Social Image')
                                                        ->image()
                                                        ->directory('seo')
                                                        ->visibility('public')
                                                        ->imageEditor(),
                                                ]),
                                        ]),
                                ])
                        ])
                        ->columns(),
                ])
                ->activeTab(1)
                ->columnSpanFull(),
        ];

        return $schema->components($result);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            TextColumn::make('title')
                ->label(__('filament.page_title'))
                ->color('primary')
                ->url(
                    url: fn(Page $record): string => $record->url(),
                    shouldOpenInNewTab: true
                )
                ->searchable(),
            TextColumn::make('status')
                ->badge()
                ->colors([
                    'success' => PageStatus::Published,
                    'warning' => PageStatus::Draft,
                    'danger' => PageStatus::Archived,
                ])
                ->label(__('Status')),
            TextColumn::make('published_at')
                ->label(__('filament.published_at'))
                ->sortable(),
        ];

        return $table
            ->query(fn() => Page::query()->with('parent'))
            ->columns($columns)
            ->defaultSort('published_at', 'desc')
            ->recordActions([
                EditAction::make()->button()->outlined(),
                DeleteAction::make()
                    ->visible(fn($record) => $record->deletable)
                    ->label(__('filament.delete')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->selectCurrentPageOnly()
            ->striped()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'view' => ViewPage::route('/{record}'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return true;
    }
}
