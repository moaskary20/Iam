<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // إحصائيات أساسية
        $stats = [
            'total_orders' => 245,
            'completed_orders' => 210,
            'pending_orders' => 25,
            'cancelled_orders' => 10,
            'total_revenue' => 15420.50,
            'current_balance' => $user->balance ?? 0,
            'monthly_profit' => 3250.00,
            'current_market' => $this->getCurrentMarketName($user->current_market_id ?? 1)
        ];

        // بيانات المبيعات الشهرية
        $monthlyData = [
            'January' => 1200,
            'February' => 1850,
            'March' => 2100,
            'April' => 1750,
            'May' => 2300,
            'June' => 1900,
            'July' => 2450,
            'August' => 2100,
            'September' => 1650,
            'October' => 1800,
            'November' => 2200,
            'December' => 2000
        ];

        // حالات الطلبات
        $orderStatus = [
            'completed' => 85,
            'pending' => 10,
            'cancelled' => 5
        ];

        // بيانات الأسواق
        $marketData = [
            'market_1' => 45,
            'market_2' => 25,
            'market_3' => 20,
            'market_4' => 10
        ];

        return view('statistics', compact('stats', 'monthlyData', 'orderStatus', 'marketData', 'user'));
    }

    private function getCurrentMarketName($marketId)
    {
        $marketNames = [
            1 => 'السوق الأول',
            2 => 'السوق الثاني',
            3 => 'السوق الثالث',
            4 => 'السوق الرابع',
            5 => 'السوق المفتوح'
        ];

        return $marketNames[$marketId] ?? 'غير محدد';
    }
}
