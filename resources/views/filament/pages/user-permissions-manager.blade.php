<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->form }}

        @if($selectedUserId)
            <x-filament::section>
                <x-slot name="heading">
                    ملخص الصلاحيات الحالية
                </x-slot>
                
                <x-slot name="description">
                    جميع الصلاحيات التي يمتلكها المستخدم (من الأدوار + الصلاحيات المباشرة)
                </x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($this->getUserPermissionsSummary() as $group => $permissions)
                        <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-800">
                            <h4 class="font-semibold mb-2 text-gray-900 dark:text-gray-100">
                                {{ match($group) {
                                    'system' => 'النظام',
                                    'users' => 'المستخدمين',
                                    'roles' => 'الأدوار', 
                                    'permissions' => 'الصلاحيات',
                                    'content' => 'المحتوى',
                                    'finance' => 'المالية',
                                    'reports' => 'التقارير',
                                    'settings' => 'الإعدادات',
                                    'general' => 'عام',
                                    default => $group
                                } }}
                            </h4>
                            <ul class="space-y-1">
                                @foreach($permissions as $permission)
                                    <li class="text-sm text-gray-600 dark:text-gray-300">
                                        • {{ $permission }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-8">
                            لا توجد صلاحيات للمستخدم المحدد
                        </div>
                    @endforelse
                </div>
            </x-filament::section>

            <div class="flex justify-end">
                <x-filament::button 
                    wire:click="save"
                    color="primary"
                    size="lg"
                >
                    حفظ التغييرات
                </x-filament::button>
            </div>
        @endif
    </div>
</x-filament-panels::page>
