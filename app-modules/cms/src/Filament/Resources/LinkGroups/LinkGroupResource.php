<?php

namespace Kaster\Cms\Filament\Resources\LinkGroups;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Kaster\Cms\Filament\Resources\LinkGroups\Pages\CreateLinkGroup;
use Kaster\Cms\Filament\Resources\LinkGroups\Pages\EditLinkGroup;
use Kaster\Cms\Filament\Resources\LinkGroups\Pages\ListLinkGroups;
use Kaster\Cms\Models\LinkGroup;

class LinkGroupResource extends Resource
{
    protected static ?string $model = LinkGroup::class;

    public static function getNavigationGroup(): ?string
    {
        return config('cms.navigation.link_group.group');
    }

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return config('cms.navigation.link_group.icon', 'heroicon-o-link');
    }

    public static function getNavigationSort(): ?int
    {
        return config('cms.navigation.link_group.sort');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Link Group'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->required(),
                        TextInput::make('position')
                            ->label(__('Position'))
                            ->placeholder(__('e.g., header, footer, sidebar'))
                            ->helperText(__('Used to identify where this link group appears')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                TextColumn::make('position')
                    ->label(__('Position'))
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('items_count')
                    ->label(__('Items'))
                    ->counts('items'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LinkItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLinkGroups::route('/'),
            'create' => CreateLinkGroup::route('/create'),
            'edit' => EditLinkGroup::route('/{record}/edit'),
        ];
    }
}
