<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Reviews\ReviewResource;
use App\Models\Review;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentReviews extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent reviews')
            ->query(fn (): Builder => Review::query()->with(['user', 'product'])->latest())
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('rating')
                    ->badge(),
                TextColumn::make('comment')
                    ->limit(70),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->recordUrl(fn (Review $record): string => ReviewResource::getUrl('view', ['record' => $record]))
            ->defaultPaginationPageOption(5)
            ->paginated([5]);
    }
}
