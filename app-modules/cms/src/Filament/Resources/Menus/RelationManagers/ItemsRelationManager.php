<?php

namespace Kaster\Cms\Filament\Resources\Menus\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Kaster\Cms\Enums\MenuItemTarget;
use Kaster\Cms\Enums\MenuItemType;
use Kaster\Cms\Models\MenuItem;
use Kaster\Cms\Models\Page;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        $targetOptions = [];
        foreach (MenuItemTarget::cases() as $target) {
            $targetOptions[$target->value] = $target->getLabel();
        }

        /** @var int $menuId */
        $menuId = $this->ownerRecord->getKey();

        return $schema
            ->components([
                Select::make('parent_id')
                    ->label(__('Parent'))
                    ->placeholder(__('Select a parent item'))
                    ->options(fn() => MenuItem::query()
                        ->where('menu_id', $menuId)
                        ->pluck('label', 'id')
                        ->toArray())
                    ->searchable(),
                TextInput::make('order')
                    ->label(__('Order'))
                    ->numeric()
                    ->nullable()
                    ->default(0),
                Select::make('target')
                    ->label(__('Target'))
                    ->options($targetOptions)
                    ->default(MenuItemTarget::Self->value),
                TextInput::make('label')
                    ->label(__('Label'))
                    ->required(),
                Section::make('link')
                    ->schema([
                        Select::make('type')
                            ->label(__('Type'))
                            ->live()
                            ->required()
                            ->options(MenuItemType::class)
                            ->default(MenuItemType::Resource),
                        TextInput::make('custom_url')
                            ->label(__('URL'))
                            ->url()
                            ->required(fn(Get $get): bool => $get('type') === MenuItemType::Custom->value)
                            ->visible(fn(Get $get): bool => $get('type') === MenuItemType::Custom->value),
                        MorphToSelect::make('model')
                            ->label(__('Page'))
                            ->visible(fn(Get $get): bool => $get('type') === MenuItemType::Resource->value)
                            ->required(fn(Get $get): bool => $get('type') === MenuItemType::Resource->value)
                            ->types([
                                Type::make(Page::class)->titleAttribute('title'),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->label(__('Label')),
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),
                TextColumn::make('parent.label')
                    ->label(__('Parent'))
                    ->placeholder('—'),
                TextColumn::make('order')
                    ->label(__('Order'))
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->headerActions([
                CreateAction::make(),
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
}
