<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Toggle::make('in_stock')
                                    ->label('In stock')
                                    ->default(true)
                                    ->required(),
                                TextInput::make('price')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('original_price')
                                    ->numeric(),
                                TextInput::make('image')
                                    ->label('Primary image URL')
                                    ->url()
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                        TagsInput::make('images')
                            ->label('Gallery image URLs')
                            ->columnSpanFull(),
                        TagsInput::make('features')
                            ->label('Features')
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
