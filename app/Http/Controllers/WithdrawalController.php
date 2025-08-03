<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10|max:10000',
            'paypal_email' => 'required|email',
            'phone' => 'required|string|min:10',
            'full_name' => 'required|string|min:3'
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        // التحقق من الرصيد
        if ($user->balance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'رصيدك غير كافي لهذا المبلغ'
            ], 400);
        }

        // التحقق من الحد الأدنى للسحب
        if ($amount < 10) {
            return response()->json([
                'success' => false,
                'message' => 'الحد الأدنى للسحب هو 10 دولار'
            ], 400);
        }

        try {
            DB::transaction(function () use ($user, $amount, $request) {
                // خصم المبلغ من المحفظة
                $user->decrement('balance', $amount);

                // إنشاء معاملة في المحفظة
                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'withdrawal',
                    'amount' => -$amount,
                    'description' => "طلب سحب $amount دولار عبر باي بال",
                    'status' => 'pending'
                ]);

                // إنشاء طلب السحب
                Withdrawal::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'paypal_email' => $request->paypal_email,
                    'phone' => $request->phone,
                    'full_name' => $request->full_name,
                    'status' => 'pending'
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'تم تقديم طلب السحب بنجاح! سيتم المعالجة خلال 48 ساعة.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء المعالجة'
            ], 500);
        }
    }
}
