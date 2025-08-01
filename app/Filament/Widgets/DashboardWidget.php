<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Wallet; 
use App\Models\Slider;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('إجمالي المستخدمين', User::count())
                ->description('جميع المستخدمين المسجلين')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('إجمالي المحافظ', Wallet::count())
                ->description('محافظ المستخدمين النشطة')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('primary'),
                
            Stat::make('شرائح الصفحة الرئيسية', Slider::count())
                ->description('الشرائح المعروضة')
                ->descriptionIcon('heroicon-m-photo')
                ->color('warning'),
        ];
    }
}