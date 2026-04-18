<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Review details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name')
                                    ->disabled()
                                    ->dehydrated(false),
                                Select::make('user_id')
                                    ->label('Customer')
                                    ->relationship('user', 'email')
                                    ->disabled()
                                    ->dehydrated(false),
                                Select::make('rating')
                                    ->options([
                                        1 => '1',
                                        2 => '2',
                                        3 => '3',
                                        4 => '4',
                                        5 => '5',
                                    ])
                                    ->required(),
                            ]),
                        Textarea::make('comment')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
