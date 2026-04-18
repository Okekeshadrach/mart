<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->square(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('price')
                    ->formatStateUsing(fn ($state): string => '$' . number_format((float) $state, 2))
                    ->sortable(),
                TextColumn::make('rating')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('review_count')
                    ->label('Reviews')
                    ->sortable(),
                IconColumn::make('in_stock')
                    ->label('In stock')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
                TernaryFilter::make('in_stock')
                    ->label('In stock'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
