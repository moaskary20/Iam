<?php

namespace App\Http\Controllers;

use App\Services\SkrillService;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class SkrillController extends Controller
{
    protected $skrillService;

    public function __construct()
    {
        try {
            $this->skrillService = new SkrillService();
        } catch (Exception $e) {
            // Skrill service not available
        }
    }

    public function createPayment(Request $request)
    {
        try {
            if (!$this->skrillService) {
                return response()->json([
                    'success' => false,
                    'message' => 'خدمة Skrill غير متاحة'
                ], 400);
            }

            $amount = $request->input('amount');
            $description = $request->input('description', 'Payment for order');
            $type = $request->input('type', 'order'); // 'order' or 'wallet_deposit'
            
            $returnUrl = route('skrill.success', ['type' => $type]);
            $cancelUrl = route('skrill.cancel');

            $payment = $this->skrillService->createPayment($amount, $description, $returnUrl, $cancelUrl);

            // حفظ بيانات الدفع في session للتحقق لاحقاً
            session([
                'skrill_payment' => [
                    'amount' => $amount,
                    'description' => $description,
                    'type' => $type,
                    'transaction_id' => $payment['transaction_id']
                ]
            ]);

            return response()->json([
                'success' => true,
                'payment_url' => $payment['payment_url'],
                'form_data' => $payment['form_data']
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في النظام: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success(Request $request)
    {
        try {
            $paymentData = session('skrill_payment');
            $paymentType = $request->input('type', $paymentData['type'] ?? 'order');

            if ($paymentType === 'wallet_deposit') {
                // Handle wallet deposit
                $user = Auth::user();
                if ($user && $paymentData) {
                    $amount = $paymentData['amount'];
                    
                    // إضافة المبلغ للمحفظة
                    $user->increment('balance', $amount);
                    
                    // إنشاء معاملة في المحفظة
                    WalletTransaction::create([
                        'user_id' => $user->id,
                        'type' => 'deposit',
                        'amount' => $amount,
                        'description' => "إيداع $amount دولار عبر Skrill",
                        'status' => 'completed'
                    ]);

                    session()->forget('skrill_payment');
                    
                    return redirect()->route('wallet')
                        ->with('success', 'تم إيداع الأموال بنجاح عبر Skrill!');
                }
            }

            // Handle regular order payment
            session()->forget('skrill_payment');
            return redirect()->route('progressive.market')
                ->with('success', 'تم الدفع بنجاح عبر Skrill!');

        } catch (Exception $e) {
            return redirect()->route('wallet')
                ->with('error', 'خطأ في عملية الدفع');
        }
    }

    public function cancel()
    {
        session()->forget('skrill_payment');
        
        return redirect()->route('wallet')
            ->with('error', 'تم إلغاء عملية الدفع');
    }

    public function status(Request $request)
    {
        try {
            // التحقق من صحة الإشعار من Skrill
            if ($this->skrillService->verifyPayment($request->all())) {
                $status = $request->input('status');
                $transactionId = $request->input('transaction_id');
                
                if ($this->skrillService->isPaymentSuccessful($status)) {
                    // Payment successful - handle accordingly
                    \Log::info('Skrill payment successful', ['transaction_id' => $transactionId]);
                } else {
                    // Payment failed
                    \Log::warning('Skrill payment failed', ['transaction_id' => $transactionId, 'status' => $status]);
                }
            }
            
            return response('OK', 200);
        } catch (Exception $e) {
            \Log::error('Skrill status error: ' . $e->getMessage());
            return response('ERROR', 400);
        }
    }
}
