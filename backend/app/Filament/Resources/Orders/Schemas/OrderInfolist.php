<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order overview')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('id')
                                    ->label('Order ID'),
                                TextEntry::make('user.email')
                                    ->label('Customer'),
                                TextEntry::make('total')
                                    ->formatStateUsing(fn ($state): string => '$' . number_format((float) $state, 2)),
                                TextEntry::make('status')
                                    ->badge()
                                    ->formatStateUsing(fn (OrderStatus|string|null $state): string => Str::title($state instanceof OrderStatus ? $state->value : (string) $state)),
                                TextEntry::make('payment_method')
                                    ->label('Payment method')
                                    ->badge()
                                    ->formatStateUsing(function (PaymentMethod|string|null $state): string {
                                        $value = $state instanceof PaymentMethod ? $state->value : (string) $state;

                                        return Str::title($value === 'cod' ? 'cash on delivery' : $value);
                                    }),
                                TextEntry::make('created_at')
                                    ->dateTime(),
                            ]),
                    ]),
                Section::make('Shipping address')
                    ->schema([
                        KeyValueEntry::make('shipping_address')
                            ->columnSpanFull(),
                    ]),
                Section::make('Order items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label('Product'),
                                TextEntry::make('quantity'),
                                TextEntry::make('price')
                                    ->formatStateUsing(fn ($state): string => '$' . number_format((float) $state, 2)),
                            ])
                            ->grid(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
