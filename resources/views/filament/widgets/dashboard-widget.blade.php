<div>
    <!-- سلايدر صور (يمكنك استبداله بمكون Livewire أو AlpineJS لاحقاً) -->
    <div class="mb-6">
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded-lg">
            <span class="text-gray-500">سلايدر صور هنا</span>
        </div>
    </div>

    <!-- بيانات المستخدم -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-4 flex flex-col gap-2">
            <div><strong>الاسم:</strong> {{ $this->getUserData()['name'] }}</div>
            <div><strong>رقم العضوية:</strong> {{ $this->getUserData()['member_id'] }}</div>
            <div><strong>الرصيد:</strong> {{ number_format($this->getUserData()['balance'], 2) }} ريال</div>
            <div><strong>السوق الحالي:</strong> {{ $this->getUserData()['market_status'] }}</div>
            <div><strong>إجمالي الأرباح:</strong> {{ number_format($this->getUserData()['total_earnings'], 2) }} ريال</div>
            <div>
                <strong>حالة التوثيق:</strong>
                <span class="inline-block w-3 h-3 rounded-full align-middle {{ $this->getUserData()['is_verified'] ? 'bg-green-500' : 'bg-red-500' }}"></span>
                <span class="ml-2">{{ $this->getUserData()['is_verified'] ? 'موثق' : 'غير موثق' }}</span>
            </div>
        </div>
    </div>
</div>
