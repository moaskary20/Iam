<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ColorColumn;
use Illuminate\Support\Collection;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    
    protected static ?string $navigationLabel = 'الأدوار';
    
    protected static ?string $modelLabel = 'دور';
    
    protected static ?string $pluralModelLabel = 'الأدوار';
    
    protected static ?string $navigationGroup = 'إدارة المستخدمين والصلاحيات';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('معلومات الدور')
                    ->description('أدخل تفاصيل الدور')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الدور (بالإنجليزية)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('مثال: admin')
                            ->helperText('يجب أن يكون باللغة الإنجليزية وبدون مسافات'),
                            
                        Forms\Components\TextInput::make('display_name')
                            ->label('الاسم المعروض')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('مثال: مدير النظام'),
                            
                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->placeholder('وصف مختصر عن هذا الدور وصلاحياته')
                            ->rows(3)
                            ->columnSpanFull(),
                            
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true)
                            ->helperText('هل هذا الدور نشط ويمكن تعيينه للمستخدمين؟'),
                            
                        Forms\Components\TextInput::make('priority')
                            ->label('الأولوية')
                            ->numeric()
                            ->default(100)
                            ->helperText('الرقم الأقل = أولوية أعلى (1 = أعلى أولوية)'),
                            
                        Forms\Components\ColorPicker::make('color')
                            ->label('لون الدور')
                            ->default('#3B82F6')
                            ->helperText('اللون المستخدم لعرض هذا الدور في الواجهة'),
                    ])
                    ->columns(2),
                    
                Section::make('الصلاحيات')
                    ->description('حدد الصلاحيات المرتبطة بهذا الدور')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('الصلاحيات المتاحة')
                            ->relationship('permissions', 'display_name')
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
                            ->descriptions(function () {
                                return Permission::where('is_active', true)
                                    ->pluck('description', 'id')
                                    ->toArray();
                            })
                            ->columns(2)
                            ->gridDirection('row')
                            ->bulkToggleable()
                            ->searchable(),
                    ])
                    ->columnSpanFull(),
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
                    
                Tables\Columns\ColorColumn::make('color')
                    ->label('اللون'),
                    
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
                    
                Tables\Columns\TextColumn::make('users_count')
                    ->label('عدد المستخدمين')
                    ->counts('users')
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('عدد الصلاحيات')
                    ->counts('permissions')
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
                    
                Tables\Filters\SelectFilter::make('permissions')
                    ->label('الصلاحيات')
                    ->relationship('permissions', 'display_name')
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
                    ->before(function (Role $record) {
                        // منع حذف الأدوار الأساسية
                        if (in_array($record->name, ['super_admin', 'admin'])) {
                            throw new \Exception('لا يمكن حذف الأدوار الأساسية للنظام');
                        }
                        
                        // إزالة الدور من جميع المستخدمين
                        $record->users()->detach();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if (in_array($record->name, ['super_admin', 'admin'])) {
                                    throw new \Exception('لا يمكن حذف الأدوار الأساسية للنظام');
                                }
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
            ->defaultSort('priority', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }
}
