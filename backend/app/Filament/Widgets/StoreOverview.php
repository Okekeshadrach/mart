<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Collection;

class StoreOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -10;

    protected ?string $heading = 'Store overview';

    protected ?string $description = 'Live operational metrics for the MART admin team.';

    protected int | array | null $columns = 4;

    protected function getStats(): array
    {
        $dailyOrderCounts = $this->getDailyOrderCounts();
        $dailyRevenue = $this->getDailyRevenue();

        $totalRevenue = (float) Order::query()->sum('total');
        $totalOrders = Order::query()->count();
        $pendingOrders = Order::query()->where('status', OrderStatus::Pending->value)->count();
        $completedOrders = Order::query()->where('status', OrderStatus::Completed->value)->count();
        $customerCount = User::query()->where('role', UserRole::Customer->value)->count();
        $newCustomersLast30Days = User::query()
            ->where('role', UserRole::Customer->value)
            ->where('created_at', '>=', now()->subDays(30)->startOfDay())
            ->count();
        $productCount = Product::query()->count();
        $outOfStockCount = Product::query()->where('in_stock', false)->count();

        return [
            Stat::make('Revenue', '$' . number_format($totalRevenue, 2))
                ->description("{$completedOrders} completed orders")
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart($dailyRevenue->all())
                ->url(OrderResource::getUrl()),
            Stat::make('Orders', number_format($totalOrders))
                ->description("{$pendingOrders} pending fulfilment")
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('warning')
                ->chart($dailyOrderCounts->all())
                ->url(OrderResource::getUrl()),
            Stat::make('Customers', number_format($customerCount))
                ->description("{$newCustomersLast30Days} new in 30 days")
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->url(UserResource::getUrl()),
            Stat::make('Products', number_format($productCount))
                ->description("{$outOfStockCount} out of stock")
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color($outOfStockCount > 0 ? 'danger' : 'success')
                ->url(ProductResource::getUrl()),
        ];
    }

    protected function getDailyOrderCounts(): Collection
    {
        $rawCounts = Order::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as aggregate')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('aggregate', 'date');

        return collect(range(6, 0))
            ->map(fn (int $daysAgo): float => (float) ($rawCounts[now()->subDays($daysAgo)->toDateString()] ?? 0));
    }

    protected function getDailyRevenue(): Collection
    {
        $rawRevenue = Order::query()
            ->selectRaw('DATE(created_at) as date, COALESCE(SUM(total), 0) as aggregate')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('aggregate', 'date');

        return collect(range(6, 0))
            ->map(fn (int $daysAgo): float => round((float) ($rawRevenue[now()->subDays($daysAgo)->toDateString()] ?? 0), 2));
    }
}
