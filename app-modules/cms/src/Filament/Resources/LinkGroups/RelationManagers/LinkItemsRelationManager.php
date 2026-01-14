<?php

namespace Kaster\Cms\Filament\Resources\LinkGroups\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Kaster\Cms\Enums\LinkItemTarget;
use Kaster\Cms\Models\LinkItem;
use Kaster\Cms\Models\Page;

class LinkItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        /** @var int $linkGroupId */
        $linkGroupId = $this->ownerRecord->getKey();

        return $schema
            ->components([
                TextInput::make('label')
                    ->label(__('Label'))
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('url')
                    ->label(__('URL'))
                    ->placeholder(__('https://example.com or /about'))
                    ->helperText(__('Enter URL directly or select a page below'))
                    ->columnSpanFull(),
                Select::make('page_selector')
                    ->label(__('Or select a page'))
                    ->placeholder(__('Select a page to auto-fill URL'))
                    ->options(fn() => Page::query()->pluck('title', 'id')->toArray())
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function (?string $state, Set $set): void {
                        if ($state) {
                            $page = Page::find($state);
                            if ($page) {
                                $set('url', $page->url());
                            }
                        }
                    })
                    ->dehydrated(false)
                    ->columnSpanFull(),
                Select::make('parent_id')
                    ->label(__('Parent'))
                    ->placeholder(__('None (root level)'))
                    ->options(fn() => LinkItem::query()
                        ->where('link_group_id', $linkGroupId)
                        ->pluck('label', 'id')
                        ->toArray())
                    ->searchable(),
                TextInput::make('order')
                    ->label(__('Order'))
                    ->numeric()
                    ->default(0),
                Select::make('target')
                    ->label(__('Open in'))
                    ->options(LinkItemTarget::class)
                    ->default(LinkItemTarget::Self),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->label(__('Label')),
                TextColumn::make('url')
                    ->label(__('URL'))
                    ->limit(40),
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
