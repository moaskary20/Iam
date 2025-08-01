<x-filament-panels::page>
    <form wire:submit.prevent="save" class="space-y-6 max-w-xl mx-auto mt-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع عمولة الإيداع</label>
                <select wire:model.defer="deposit_fee_type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="fixed">مبلغ ثابت</option>
                    <option value="percent">نسبة مئوية (%)</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">قيمة عمولة الإيداع</label>
                <input type="number" step="0.01" wire:model.defer="deposit_fee" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع عمولة السحب</label>
                <select wire:model.defer="withdraw_fee_type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="fixed">مبلغ ثابت</option>
                    <option value="percent">نسبة مئوية (%)</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">قيمة عمولة السحب</label>
                <input type="number" step="0.01" wire:model.defer="withdraw_fee" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </div>
            <x-filament::button type="submit" color="primary">
                حفظ التغييرات
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
