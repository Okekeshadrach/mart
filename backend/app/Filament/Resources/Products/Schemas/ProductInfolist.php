<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product overview')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                ImageEntry::make('image')
                                    ->label('Primary image')
                                    ->columnSpanFull(),
                                TextEntry::make('name'),
                                TextEntry::make('slug'),
                                TextEntry::make('category.name')
                                    ->label('Category'),
                                IconEntry::make('in_stock')
                                    ->label('In stock')
                                    ->boolean(),
                                TextEntry::make('price')
                                    ->formatStateUsing(fn ($state): string => '$' . number_format((float) $state, 2)),
                                TextEntry::make('original_price')
                                    ->formatStateUsing(fn ($state): string => $state !== null ? '$' . number_format((float) $state, 2) : '-'),
                                TextEntry::make('rating')
                                    ->numeric(decimalPlaces: 2),
                                TextEntry::make('review_count')
                                    ->label('Review count'),
                                TextEntry::make('features')
                                    ->bulleted()
                                    ->listWithLineBreaks()
                                    ->columnSpanFull(),
                                TextEntry::make('images')
                                    ->label('Gallery image URLs')
                                    ->bulleted()
                                    ->listWithLineBreaks()
                                    ->columnSpanFull(),
                                TextEntry::make('description')
                                    ->columnSpanFull(),
                                TextEntry::make('updated_at')
                                    ->dateTime()
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
