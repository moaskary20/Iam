<x-filament-panels::page>
    <form wire:submit.prevent="withdraw">
        <div class="space-y-4">
            <x-filament::forms.field-wrapper label="المبلغ" required>
                <input type="number" wire:model.defer="amount" required 
                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </x-filament::forms.field-wrapper>
            
            <x-filament::button type="submit" color="danger">
                سحب
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
