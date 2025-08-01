<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">مرحباً في لوحة التحكم</h1>
            <p class="text-gray-600">أهلاً وسهلاً بك في نظام الإدارة</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-500 text-white p-6 rounded-lg">
                <h3 class="text-lg font-semibold">المستخدمون</h3>
                <p class="text-2xl">{{ \App\Models\User::count() }}</p>
            </div>
            
            <div class="bg-green-500 text-white p-6 rounded-lg">
                <h3 class="text-lg font-semibold">المحافظ</h3>
                <p class="text-2xl">{{ \App\Models\Wallet::count() }}</p>
            </div>
            
            <div class="bg-purple-500 text-white p-6 rounded-lg">
                <h3 class="text-lg font-semibold">الشرائح</h3>
                <p class="text-2xl">{{ \App\Models\Slider::count() }}</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">معلومات المستخدم الحالي</h2>
            <div class="space-y-2">
                <p><strong>الاسم:</strong> {{ auth()->user()->name }}</p>
                <p><strong>البريد الإلكتروني:</strong> {{ auth()->user()->email }}</p>
                <p><strong>تاريخ التسجيل:</strong> {{ auth()->user()->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
