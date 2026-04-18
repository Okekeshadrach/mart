<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Review overview')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label('Product'),
                                TextEntry::make('user.email')
                                    ->label('Customer'),
                                TextEntry::make('rating')
                                    ->badge(),
                                TextEntry::make('created_at')
                                    ->dateTime(),
                                TextEntry::make('comment')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
