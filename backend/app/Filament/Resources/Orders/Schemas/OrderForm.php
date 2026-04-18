<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order management')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->options(collect(OrderStatus::cases())->mapWithKeys(fn (OrderStatus $status) => [
                                        $status->value => Str::title($status->value),
                                    ])->all())
                                    ->required(),
                                Select::make('payment_method')
                                    ->label('Payment method')
                                    ->options(collect(PaymentMethod::cases())->mapWithKeys(fn (PaymentMethod $method) => [
                                        $method->value => Str::title($method->value === 'cod' ? 'cash on delivery' : $method->value),
                                    ])->all())
                                    ->required(),
                            ]),
                    ]),
                Section::make('Shipping address')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('shipping_address.first_name')
                                    ->label('First name')
                                    ->required(),
                                TextInput::make('shipping_address.last_name')
                                    ->label('Last name')
                                    ->required(),
                                TextInput::make('shipping_address.email')
                                    ->label('Email')
                                    ->email()
                                    ->required(),
                                TextInput::make('shipping_address.city')
                                    ->label('City')
                                    ->required(),
                                TextInput::make('shipping_address.zip')
                                    ->label('ZIP')
                                    ->required(),
                                TextInput::make('shipping_address.country')
                                    ->label('Country')
                                    ->required(),
                            ]),
                        Textarea::make('shipping_address.address')
                            ->label('Address')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
