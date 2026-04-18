<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('items_count')
                    ->label('Items')
                    ->sortable(),
                TextColumn::make('total')
                    ->formatStateUsing(fn ($state): string => '$' . number_format((float) $state, 2))
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus|string|null $state): string => Str::title($state instanceof OrderStatus ? $state->value : (string) $state)),
                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge()
                    ->formatStateUsing(function (PaymentMethod|string|null $state): string {
                        $value = $state instanceof PaymentMethod ? $state->value : (string) $state;

                        return Str::title($value === 'cod' ? 'cash on delivery' : $value);
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('payment_method')
                    ->label('Payment method')
                    ->options([
                        'card' => 'Card',
                        'paypal' => 'PayPal',
                        'cod' => 'Cash on delivery',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
