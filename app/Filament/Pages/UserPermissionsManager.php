<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class UserPermissionsManager extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    
    protected static ?string $navigationLabel = 'إدارة صلاحيات المستخدمين';
    
    protected static ?string $title = 'إدارة صلاحيات المستخدمين';
    
    protected static ?string $navigationGroup = 'إدارة المستخدمين والصلاحيات';
    
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.user-permissions-manager';

    public ?int $selectedUserId = null;
    public array $selectedRoles = [];
    public array $selectedPermissions = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('اختيار المستخدم')
                    ->description('اختر المستخدم لإدارة أدواره وصلاحياته')
                    ->schema([
                        Select::make('selectedUserId')
                            ->label('المستخدم')
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->loadUserPermissions($state);
                            })
                            ->placeholder('اختر مستخدماً'),
                    ]),

                Section::make('الأدوار')
                    ->description('إدارة أدوار المستخدم')
                    ->schema([
                        CheckboxList::make('selectedRoles')
                            ->label('الأدوار المتاحة')
                            ->options(Role::where('is_active', true)->orderBy('priority')->pluck('display_name', 'id'))
                            ->descriptions(Role::where('is_active', true)->pluck('description', 'id')->toArray())
                            ->columns(2)
                            ->reactive(),
                    ])
                    ->visible(fn () => $this->selectedUserId !== null),

                Section::make('الصلاحيات المباشرة')
                    ->description('صلاحيات إضافية مباشرة للمستخدم')
                    ->schema([
                        CheckboxList::make('selectedPermissions')
                            ->label('الصلاحيات المتاحة')
                            ->options(function () {
                                return Permission::where('is_active', true)
                                    ->orderBy('group')
                                    ->orderBy('priority')
                                    ->get()
                                    ->groupBy('group')
                                    ->map(function ($permissions, $group) {
                                        return $permissions->pluck('display_name', 'id')->toArray();
                                    })
                                    ->toArray();
                            })
                            ->descriptions(Permission::where('is_active', true)->pluck('description', 'id')->toArray())
                            ->columns(2),
                    ])
                    ->visible(fn () => $this->selectedUserId !== null),
            ])
            ->statePath('data');
    }

    public function loadUserPermissions(?int $userId): void
    {
        if (!$userId) {
            $this->selectedRoles = [];
            $this->selectedPermissions = [];
            return;
        }

        $user = User::find($userId);
        if ($user) {
            $this->selectedRoles = $user->roles()->pluck('roles.id')->toArray();
            $this->selectedPermissions = $user->permissions()->pluck('permissions.id')->toArray();
        }
    }

    public function save(): void
    {
        if (!$this->selectedUserId) {
            Notification::make()
                ->title('خطأ')
                ->body('يجب اختيار مستخدم أولاً')
                ->danger()
                ->send();
            return;
        }

        $user = User::find($this->selectedUserId);
        if (!$user) {
            Notification::make()
                ->title('خطأ')
                ->body('المستخدم غير موجود')
                ->danger()
                ->send();
            return;
        }

        // تحديث الأدوار
        $user->roles()->sync($this->selectedRoles);

        // تحديث الصلاحيات المباشرة
        $user->permissions()->sync($this->selectedPermissions);

        Notification::make()
            ->title('تم الحفظ بنجاح')
            ->body('تم تحديث أدوار وصلاحيات المستخدم بنجاح')
            ->success()
            ->send();
    }

    public function getUserPermissionsSummary(): array
    {
        if (!$this->selectedUserId) {
            return [];
        }

        $user = User::find($this->selectedUserId);
        if (!$user) {
            return [];
        }

        $allPermissions = $user->getAllPermissions();
        $groupedPermissions = $allPermissions->groupBy('group');

        return $groupedPermissions->map(function ($permissions) {
            return $permissions->pluck('display_name')->toArray();
        })->toArray();
    }

    public static function canAccess(): bool
    {
        return auth()->check() && 
               (auth()->user()->isSuperAdmin() || 
                auth()->user()->hasPermission('manage_roles') || 
                auth()->user()->hasPermission('assign_roles'));
    }
}
