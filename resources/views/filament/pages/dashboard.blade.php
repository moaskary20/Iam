<x-filament-panels::page>
    @if(auth()->check())
        <div class="space-y-6">
            <div class="text-lg font-semibold">مرحباً بك في لوحة التحكم</div>
            
            @if(class_exists('App\Filament\Widgets\DashboardWidget'))
                @livewire(App\Filament\Widgets\DashboardWidget::class)
            @endif
        </div>
    @else
        <div class="text-center py-8">
            <p>يرجى تسجيل الدخول للوصول إلى لوحة التحكم</p>
        </div>
    @endif
</x-filament-panels::page>
