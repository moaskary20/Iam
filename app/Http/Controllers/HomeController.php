<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Slider;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $sliders = Slider::where('active', 1)->orderBy('order')->get();
        
        // Get statistics based on user authentication
        if ($user) {
            // Get user-specific statistics
            $statistics = $this->getUserStatistics($user);
        } else {
            // Get general system statistics for guests
            $statistics = $this->getRealtimeStatistics();
        }
        
        return view('welcome', compact('user', 'sliders', 'statistics'));
    }
    
    public function getStatistics()
    {
        $user = auth()->user();
        
        // Return user-specific or system statistics based on authentication
        if ($user) {
            $statistics = $this->getUserStatistics($user);
        } else {
            $statistics = $this->getRealtimeStatistics();
        }
        
        return response()->json($statistics);
    }
    
    private function getUserStatistics($user)
    {
        // Get user's wallet
        $wallet = $user->wallet;
        
        // User's total balance
        $userBalance = $user->balance ?? 0;
        
        // User's transactions count
        $userTransactions = $wallet ? $wallet->transactions()->count() : 0;
        
        // User's successful transactions
        $successfulTransactions = $wallet ? $wallet->transactions()->where('status', 'completed')->count() : 0;
        
        // User's pending transactions
        $pendingTransactions = $wallet ? $wallet->transactions()->where('status', 'pending')->count() : 0;
        
        // User's total deposits
        $totalDeposits = $wallet ? $wallet->transactions()->where('type', 'deposit')->sum('amount') : 0;
        
        // User's total withdrawals
        $totalWithdrawals = $wallet ? $wallet->transactions()->where('type', 'withdraw')->sum('amount') : 0;
        
        // User's transactions this month
        $monthlyTransactions = $wallet ? $wallet->transactions()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() : 0;
        
        // User's transactions today
        $todayTransactions = $wallet ? $wallet->transactions()
            ->whereDate('created_at', today())
            ->count() : 0;
        
        // User's account age in days
        $accountAge = $user->created_at->diffInDays(now());
        
        // User's last transaction date
        $lastTransaction = $wallet ? $wallet->transactions()->latest()->first() : null;
        $daysSinceLastTransaction = $lastTransaction ? $lastTransaction->created_at->diffInDays(now()) : null;
        
        return [
            'user_balance' => $userBalance,
            'user_transactions' => $userTransactions,
            'successful_transactions' => $successfulTransactions,
            'pending_transactions' => $pendingTransactions,
            'total_deposits' => $totalDeposits,
            'total_withdrawals' => $totalWithdrawals,
            'monthly_transactions' => $monthlyTransactions,
            'today_transactions' => $todayTransactions,
            'account_age' => $accountAge,
            'days_since_last_transaction' => $daysSinceLastTransaction,
            'average_transaction' => $userTransactions > 0 ? ($totalDeposits + $totalWithdrawals) / $userTransactions : 0,
            'net_deposits' => $totalDeposits - $totalWithdrawals,
        ];
    }
    
    private function getRealtimeStatistics()
    {
        // Get total users count
        $totalUsers = User::count();
        
        // Get total balance across all users
        $totalBalance = User::sum('balance') ?? 0;
        
        // Get total transactions count
        $totalTransactions = WalletTransaction::count();
        
        // Get active users (users who have logged in recently or have balance > 0)
        $activeUsers = User::where(function($query) {
            $query->where('balance', '>', 0)
                  ->orWhere('updated_at', '>=', now()->subDays(30));
        })->count();
        
        // Get average user balance
        $averageBalance = $totalUsers > 0 ? $totalBalance / $totalUsers : 0;
        
        // Get recent transactions (last 7 days)
        $recentTransactions = WalletTransaction::where('created_at', '>=', now()->subDays(7))->count();
        
        // Get total revenue (sum of all deposits)
        $totalRevenue = WalletTransaction::where('type', 'deposit')->sum('amount') ?? 0;
        
        // Get pending transactions
        $pendingTransactions = WalletTransaction::where('status', 'pending')->count();
        
        // Get today's new users
        $todayUsers = User::whereDate('created_at', today())->count();
        
        // Get this month's transactions
        $monthlyTransactions = WalletTransaction::whereMonth('created_at', now()->month)
                                               ->whereYear('created_at', now()->year)
                                               ->count();
        
        // Get successful vs failed transactions
        $successfulTransactions = WalletTransaction::where('status', 'completed')->count();
        $failedTransactions = WalletTransaction::where('status', 'failed')->count();
        
        return [
            'total_users' => $totalUsers,
            'total_balance' => $totalBalance,
            'total_transactions' => $totalTransactions,
            'active_users' => $activeUsers,
            'average_balance' => $averageBalance,
            'recent_transactions' => $recentTransactions,
            'total_revenue' => $totalRevenue,
            'pending_transactions' => $pendingTransactions,
            'today_users' => $todayUsers,
            'monthly_transactions' => $monthlyTransactions,
            'successful_transactions' => $successfulTransactions,
            'failed_transactions' => $failedTransactions,
        ];
    }
}
