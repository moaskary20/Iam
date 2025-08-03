<?php

namespace App\Http\Controllers;

use App\Services\PayPalService;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class PayPalController extends Controller
{
    protected $paypalService;

    public function __construct()
    {
        try {
            $this->paypalService = new PayPalService();
        } catch (Exception $e) {
            // PayPal service not available
        }
    }

    public function createPayment(Request $request)
    {
        try {
            if (!$this->paypalService) {
                return response()->json([
                    'success' => false,
                    'message' => 'خدمة باي بال غير متاحة'
                ], 400);
            }

            $amount = $request->input('amount');
            $description = $request->input('description', 'Payment for order');
            $type = $request->input('type', 'order'); // 'order' or 'wallet_deposit'
            
            // Store payment info in session
            session([
                'paypal_payment_type' => $type,
                'paypal_amount' => $amount,
                'paypal_description' => $description
            ]);
            
            $returnUrl = route('paypal.success');
            $cancelUrl = route('paypal.cancel');

            $payment = $this->paypalService->createPayment($amount, $description, $returnUrl, $cancelUrl);

            if (isset($payment['links'])) {
                foreach ($payment['links'] as $link) {
                    if ($link['rel'] === 'approval_url') {
                        return response()->json([
                            'success' => true,
                            'approval_url' => $link['href'],
                            'payment_id' => $payment['id']
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء عملية الدفع'
            ], 400);

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
            $paymentId = $request->input('paymentId');
            $payerId = $request->input('PayerID');

            $result = $this->paypalService->executePayment($paymentId, $payerId);

            if (isset($result['state']) && $result['state'] === 'approved') {
                $paymentType = session('paypal_payment_type', 'order');
                $amount = session('paypal_amount');
                
                if ($paymentType === 'wallet_deposit') {
                    // Handle wallet deposit
                    $user = Auth::user();
                    if ($user) {
                        // Add to user balance
                        $user->balance += $amount;
                        $user->save();
                        
                        // Create transaction record
                        WalletTransaction::create([
                            'user_id' => $user->id,
                            'type' => 'deposit',
                            'amount' => $amount,
                            'balance_after' => $user->balance,
                            'description' => 'إيداع عبر باي بال',
                            'payment_method' => 'paypal',
                            'reference' => $paymentId
                        ]);
                        
                        // Clear session
                        session()->forget(['paypal_payment_type', 'paypal_amount', 'paypal_description']);
                        
                        return redirect()->route('wallet')
                            ->with('success', 'تم إيداع $' . number_format($amount, 2) . ' بنجاح في محفظتك!');
                    }
                } else {
                    // Handle regular order payment
                    return redirect()->route('progressive.market')
                        ->with('success', 'تم الدفع بنجاح عبر باي بال!');
                }
            }

            return redirect()->route('wallet')
                ->with('error', 'فشل في تأكيد الدفع');

        } catch (Exception $e) {
            return redirect()->route('wallet')
                ->with('error', 'خطأ في عملية الدفع');
        }
    }

    public function cancel()
    {
        $paymentType = session('paypal_payment_type', 'order');
        
        // Clear session
        session()->forget(['paypal_payment_type', 'paypal_amount', 'paypal_description']);
        
        if ($paymentType === 'wallet_deposit') {
            return redirect()->route('deposit')
                ->with('error', 'تم إلغاء عملية الإيداع');
        }
        
        return redirect()->route('progressive.market')
            ->with('error', 'تم إلغاء عملية الدفع');
    }
}
