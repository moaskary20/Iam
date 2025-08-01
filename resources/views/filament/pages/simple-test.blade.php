<x-filament-panels::page>
    <div class="space-y-4">
        <h1 class="text-2xl font-bold">مرحباً! هذه صفحة اختبار</h1>
        <p>إذا كنت ترى هذا النص، فإن Filament يعمل بشكل صحيح.</p>
        <div class="bg-blue-100 p-4 rounded">
            <p>المستخدم الحالي: {{ auth()->user()->name ?? 'غير محدد' }}</p>
            <p>البريد الإلكتروني: {{ auth()->user()->email ?? 'غير محدد' }}</p>
        </div>
    </div>
</x-filament-panels::page>
