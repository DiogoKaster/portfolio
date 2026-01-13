<?php

namespace Kaster\Cms\Filament\Resources\Menus;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Kaster\Cms\Filament\Resources\Menus\Pages\CreateMenu;
use Kaster\Cms\Filament\Resources\Menus\Pages\EditMenu;
use Kaster\Cms\Filament\Resources\Menus\Pages\ListMenus;
use Kaster\Cms\Models\Menu;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    public static function getNavigationGroup(): ?string
    {
        return config('cms.navigation.menu.group');
    }

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return config('cms.navigation.menu.icon', 'heroicon-o-rectangle-stack');
    }

    public static function getNavigationSort(): ?int
    {
        return config('cms.navigation.menu.sort');
    }

    public static function form(Schema $schema): Schema
    {

        $parametersTab = [
            TextInput::make('title')
                ->label(__('filament.menu_title'))
                ->live(debounce: 500)
                ->afterStateUpdated(fn(Set $set, ?string $state): mixed => $set('slug', Str::slug(strval($state))))
                ->required(),
            TextInput::make('slug')
                ->label(__('filament.menu_slug'))
                ->required(),
        ];

        return $schema
            ->components([
                Section::make(__('Menu'))
                    ->columns(1)
                    ->schema($parametersTab),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            TextColumn::make('title')
                ->label(__('filament.menu_title')),
        ];

        return $table
            ->columns($columns)
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label(__('filament.delete')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
