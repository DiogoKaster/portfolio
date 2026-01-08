<?php

namespace Kaster\Cms\Filament;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;

class CommonFields
{
    public static function getCommonSeoFields(): array
    {
        return [
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
        ];
    }
}
