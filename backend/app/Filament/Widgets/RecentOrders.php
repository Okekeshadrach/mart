<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class RecentOrders extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent orders')
            ->query(fn (): Builder => Order::query()->with('user')->latest())
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('total')
                    ->formatStateUsing(fn ($state): string => '$' . number_format((float) $state, 2)),
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
                    ->dateTime(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->recordUrl(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
            ->defaultPaginationPageOption(5)
            ->paginated([5]);
    }
}
