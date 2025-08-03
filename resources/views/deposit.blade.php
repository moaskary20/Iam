@extends('layouts.app')

@section('title', 'إيداع الأموال')

@section('content')
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إيداع الأموال</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .amount-section {
            background: #f8f9ff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .amount-label {
            color: #666;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .amount-input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1.2rem;
            text-align: center;
            font-weight: bold;
            color: #333;
        }

        .amount-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
        }

        .quick-amounts {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 15px;
        }

        .quick-amount-btn {
            padding: 10px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .quick-amount-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .payment-methods {
            margin-bottom: 25px;
        }

        .method-title {
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .payment-method {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .payment-method:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .payment-method.selected {
            border-color: #667eea;
            background: #f8f9ff;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        .method-icon {
            font-size: 1.5rem;
            width: 40px;
            text-align: center;
        }

        .method-info {
            flex: 1;
        }

        .method-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .method-description {
            color: #666;
            font-size: 0.9rem;
        }

        .deposit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .deposit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .deposit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 768px) {
            .container {
                margin: 0 10px;
                padding: 20px;
            }
            
            .quick-amounts {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💳 إيداع الأموال</h1>
            <p>اختر المبلغ وطريقة الدفع</p>
        </div>

        <div class="amount-section">
            <div class="amount-label">المبلغ المراد إيداعه (بالدولار)</div>
            <input type="number" id="deposit-amount" class="amount-input" placeholder="0.00" min="1" step="0.01">
            
            <div class="quick-amounts">
                <button class="quick-amount-btn" onclick="setAmount(10)">$10</button>
                <button class="quick-amount-btn" onclick="setAmount(25)">$25</button>
                <button class="quick-amount-btn" onclick="setAmount(50)">$50</button>
                <button class="quick-amount-btn" onclick="setAmount(100)">$100</button>
                <button class="quick-amount-btn" onclick="setAmount(250)">$250</button>
                <button class="quick-amount-btn" onclick="setAmount(500)">$500</button>
            </div>
        </div>

        <div class="payment-methods">
            <div class="method-title">اختر طريقة الدفع</div>
            
            <div class="payment-method" onclick="selectPaymentMethod('paypal')" id="method-paypal">
                <div class="method-icon">💙</div>
                <div class="method-info">
                    <div class="method-name">باي بال (PayPal)</div>
                    <div class="method-description">دفع آمن وسريع عبر باي بال</div>
                </div>
            </div>

            <div class="payment-method" onclick="selectPaymentMethod('card')" id="method-card">
                <div class="method-icon">💳</div>
                <div class="method-info">
                    <div class="method-name">بطاقة ائتمان</div>
                    <div class="method-description">فيزا، ماستركارد، أمريكان إكسبريس</div>
                </div>
            </div>

            <div class="payment-method" onclick="selectPaymentMethod('bank')" id="method-bank">
                <div class="method-icon">🏦</div>
                <div class="method-info">
                    <div class="method-name">حوالة بنكية</div>
                    <div class="method-description">تحويل مباشر من البنك</div>
                </div>
            </div>
        </div>

        <button class="deposit-btn" id="deposit-btn" onclick="processDeposit()" disabled>
            إيداع الأموال
        </button>
    </div>

    <script>
        let selectedMethod = null;
        let selectedAmount = 0;

        function setAmount(amount) {
            document.getElementById('deposit-amount').value = amount;
            selectedAmount = amount;
            updateDepositButton();
            
            // Add visual feedback
            event.target.style.transform = 'scale(0.95)';
            setTimeout(() => {
                event.target.style.transform = 'scale(1)';
            }, 150);
        }

        function selectPaymentMethod(method) {
            // Remove previous selection
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Add selection to clicked method
            document.getElementById(`method-${method}`).classList.add('selected');
            selectedMethod = method;
            updateDepositButton();
        }

        function updateDepositButton() {
            const btn = document.getElementById('deposit-btn');
            const amount = parseFloat(document.getElementById('deposit-amount').value) || 0;
            
            if (amount > 0 && selectedMethod) {
                btn.disabled = false;
                btn.textContent = `إيداع $${amount.toFixed(2)}`;
            } else {
                btn.disabled = true;
                btn.textContent = 'إيداع الأموال';
            }
        }

        function processDeposit() {
            const amount = parseFloat(document.getElementById('deposit-amount').value);
            
            if (!amount || amount <= 0) {
                alert('يرجى إدخال مبلغ صحيح');
                return;
            }

            if (!selectedMethod) {
                alert('يرجى اختيار طريقة الدفع');
                return;
            }

            const btn = document.getElementById('deposit-btn');
            btn.disabled = true;
            btn.textContent = 'جاري المعالجة...';

            if (selectedMethod === 'paypal') {
                processPayPalDeposit(amount);
            } else {
                // Handle other payment methods
                alert(`سيتم تفعيل ${getMethodName(selectedMethod)} قريباً`);
                btn.disabled = false;
                updateDepositButton();
            }
        }

        function processPayPalDeposit(amount) {
            const formData = new FormData();
            formData.append('amount', amount);
            formData.append('description', `إيداع $${amount} في المحفظة`);
            formData.append('type', 'wallet_deposit');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/paypal/create-payment', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // توجيه المستخدم إلى باي بال
                    window.location.href = data.approval_url;
                } else {
                    alert(data.message || 'فشل في إنشاء عملية الدفع عبر باي بال');
                    document.getElementById('deposit-btn').disabled = false;
                    updateDepositButton();
                }
            })
            .catch(error => {
                console.error('PayPal Error:', error);
                alert('حدث خطأ أثناء التوصيل مع باي بال');
                document.getElementById('deposit-btn').disabled = false;
                updateDepositButton();
            });
        }

        function getMethodName(method) {
            const names = {
                'card': 'بطاقة الائتمان',
                'bank': 'الحوالة البنكية',
                'paypal': 'باي بال'
            };
            return names[method] || method;
        }

        // Listen to amount input changes
        document.getElementById('deposit-amount').addEventListener('input', function() {
            selectedAmount = parseFloat(this.value) || 0;
            updateDepositButton();
        });
    </script>

    <!-- Mobile Navigation -->
    <div class="d-block d-md-none">
        <x-mobile-nav />
    </div>
</body>
</html>
@endsection
