<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User overview')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('email'),
                                TextEntry::make('role')
                                    ->badge()
                                    ->formatStateUsing(fn (UserRole|string|null $state): string => Str::title($state instanceof UserRole ? $state->value : (string) $state)),
                                TextEntry::make('orders_count')
                                    ->label('Orders'),
                                TextEntry::make('reviews_count')
                                    ->label('Reviews'),
                                TextEntry::make('created_at')
                                    ->dateTime(),
                            ]),
                    ]),
            ]);
    }
}
