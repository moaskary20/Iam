<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Collection;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    
    protected static ?string $navigationLabel = 'الصلاحيات';
    
    protected static ?string $modelLabel = 'صلاحية';
    
    protected static ?string $pluralModelLabel = 'الصلاحيات';
    
    protected static ?string $navigationGroup = 'إدارة المستخدمين والصلاحيات';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('معلومات الصلاحية')
                    ->description('أدخل تفاصيل الصلاحية')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الصلاحية (بالإنجليزية)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('مثال: manage_users')
                            ->helperText('يجب أن يكون باللغة الإنجليزية وبدون مسافات'),
                            
                        Forms\Components\TextInput::make('display_name')
                            ->label('الاسم المعروض')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('مثال: إدارة المستخدمين'),
                            
                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->placeholder('وصف مختصر عن هذه الصلاحية')
                            ->rows(3)
                            ->columnSpanFull(),
                            
                        Forms\Components\Select::make('group')
                            ->label('المجموعة')
                            ->required()
                            ->options([
                                'system' => 'النظام',
                                'users' => 'المستخدمين',
                                'roles' => 'الأدوار',
                                'permissions' => 'الصلاحيات',
                                'content' => 'المحتوى',
                                'finance' => 'المالية',
                                'reports' => 'التقارير',
                                'settings' => 'الإعدادات',
                                'general' => 'عام',
                                'other' => 'أخرى'
                            ])
                            ->default('general')
                            ->helperText('مجموعة تصنيف الصلاحية'),
                            
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true)
                            ->helperText('هل هذه الصلاحية نشطة ويمكن تعيينها؟'),
                            
                        Forms\Components\TextInput::make('priority')
                            ->label('الأولوية')
                            ->numeric()
                            ->default(100)
                            ->helperText('الرقم الأقل = أولوية أعلى (1 = أعلى أولوية)'),
                            
                        Forms\Components\TextInput::make('icon')
                            ->label('الأيقونة')
                            ->placeholder('heroicon-o-key')
                            ->helperText('اسم الأيقونة من Heroicons'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('display_name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('المعرف')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                    
                Tables\Columns\TextColumn::make('group')
                    ->label('المجموعة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'system' => 'danger',
                        'users' => 'info',
                        'roles' => 'warning',
                        'permissions' => 'primary',
                        'content' => 'success',
                        'finance' => 'warning',
                        'reports' => 'info',
                        'settings' => 'gray',
                        'general' => 'secondary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'system' => 'النظام',
                        'users' => 'المستخدمين',
                        'roles' => 'الأدوار',
                        'permissions' => 'الصلاحيات',
                        'content' => 'المحتوى',
                        'finance' => 'المالية',
                        'reports' => 'التقارير',
                        'settings' => 'الإعدادات',
                        'general' => 'عام',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('priority')
                    ->label('الأولوية')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state <= 10 => 'danger',
                        $state <= 50 => 'warning',
                        default => 'gray',
                    }),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('roles_count')
                    ->label('عدد الأدوار')
                    ->counts('roles')
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('users_count')
                    ->label('المستخدمين المباشرين')
                    ->counts('users')
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('الحالة')
                    ->placeholder('الكل')
                    ->trueLabel('نشط')
                    ->falseLabel('غير نشط'),
                    
                Tables\Filters\SelectFilter::make('group')
                    ->label('المجموعة')
                    ->options([
                        'system' => 'النظام',
                        'users' => 'المستخدمين',
                        'roles' => 'الأدوار',
                        'permissions' => 'الصلاحيات',
                        'content' => 'المحتوى',
                        'finance' => 'المالية',
                        'reports' => 'التقارير',
                        'settings' => 'الإعدادات',
                        'general' => 'عام',
                    ])
                    ->multiple(),
                    
                Tables\Filters\SelectFilter::make('roles')
                    ->label('الأدوار')
                    ->relationship('roles', 'display_name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->before(function (Permission $record) {
                        // منع حذف الصلاحيات الأساسية
                        if (in_array($record->name, ['super_admin', 'access_admin'])) {
                            throw new \Exception('لا يمكن حذف الصلاحيات الأساسية للنظام');
                        }
                        
                        // إزالة الصلاحية من جميع الأدوار والمستخدمين
                        $record->roles()->detach();
                        $record->users()->detach();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if (in_array($record->name, ['super_admin', 'access_admin'])) {
                                    throw new \Exception('لا يمكن حذف الصلاحيات الأساسية للنظام');
                                }
                                $record->roles()->detach();
                                $record->users()->detach();
                            }
                        }),
                        
                    Tables\Actions\BulkAction::make('activate')
                        ->label('تفعيل المحدد')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true])),
                        
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('إلغاء تفعيل المحدد')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('group', 'asc')
            ->groups([
                Tables\Grouping\Group::make('group')
                    ->label('المجموعة')
                    ->getTitleFromRecordUsing(fn (Permission $record): string => match ($record->group) {
                        'system' => 'النظام',
                        'users' => 'المستخدمين',
                        'roles' => 'الأدوار',
                        'permissions' => 'الصلاحيات',
                        'content' => 'المحتوى',
                        'finance' => 'المالية',
                        'reports' => 'التقارير',
                        'settings' => 'الإعدادات',
                        'general' => 'عام',
                        default => $record->group,
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }
}
