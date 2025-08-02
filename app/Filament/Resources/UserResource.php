<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'المستخدمون';
    
    protected static ?string $modelLabel = 'مستخدم';
    
    protected static ?string $pluralModelLabel = 'المستخدمون';
    
    protected static ?string $navigationGroup = 'إدارة المستخدمين';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('الاسم الأول')
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $firstName = $state ?? '';
                                $lastName = $get('last_name') ?? '';
                                $fullName = trim($firstName . ' ' . $lastName);
                                if (!empty($fullName)) {
                                    $set('name', $fullName);
                                }
                            }),
                        Forms\Components\TextInput::make('last_name')
                            ->label('الاسم الأخير')
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $firstName = $get('first_name') ?? '';
                                $lastName = $state ?? '';
                                $fullName = trim($firstName . ' ' . $lastName);
                                if (!empty($fullName)) {
                                    $set('name', $fullName);
                                }
                            }),
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم الكامل')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (empty($state)) {
                                    $firstName = $get('first_name') ?? '';
                                    $lastName = $get('last_name') ?? '';
                                    $fullName = trim($firstName . ' ' . $lastName);
                                    if (!empty($fullName)) {
                                        $set('name', $fullName);
                                    }
                                }
                            }),
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->tel(),
                        Forms\Components\TextInput::make('country')
                            ->label('البلد'),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('معلومات الحساب')
                    ->schema([
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('تاريخ تأكيد البريد'),
                        Forms\Components\TextInput::make('password')
                            ->label('كلمة المرور')
                            ->password()
                            ->required()
                            ->hiddenOn('edit'),
                        Forms\Components\TextInput::make('invite_code')
                            ->label('كود الدعوة'),
                        Forms\Components\Toggle::make('is_verified')
                            ->label('مُتحقق منه')
                            ->default(false),
                        Forms\Components\Select::make('verification_status')
                            ->label('حالة التحقق')
                            ->options([
                                'pending' => 'في الانتظار',
                                'verified' => 'مُتحقق',
                                'rejected' => 'مرفوض',
                            ]),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('التقدم في الأسواق')
                    ->schema([
                        Forms\Components\TextInput::make('current_market_id')
                            ->label('السوق الحالي')
                            ->numeric()
                            ->default(1),
                        Forms\Components\TextInput::make('current_product_index')
                            ->label('فهرس المنتج الحالي')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('balance')
                            ->label('الرصيد')
                            ->numeric()
                            ->default(0)
                            ->prefix('$'),
                    ])
                    ->columns(3),
                    
                Forms\Components\Section::make('الملفات')
                    ->schema([
                        Forms\Components\FileUpload::make('id_image_path')
                            ->label('صورة الهوية')
                            ->image(),
                        Forms\Components\FileUpload::make('profile_photo')
                            ->label('الصورة الشخصية')
                            ->image(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->label('الصورة')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->label('البلد')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->label('مُتحقق')
                    ->boolean(),
                Tables\Columns\BadgeColumn::make('verification_status')
                    ->label('حالة التحقق')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pending' => 'في الانتظار',
                        'verified' => 'مُتحقق',
                        'rejected' => 'مرفوض',
                        default => 'غير محدد',
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'verified',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('current_market_id')
                    ->label('السوق الحالي')
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->label('الرصيد')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('حالة التحقق')
                    ->trueLabel('متحقق')
                    ->falseLabel('غير متحقق')
                    ->nullable(),
                Tables\Filters\SelectFilter::make('verification_status')
                    ->label('حالة المراجعة')
                    ->options([
                        'pending' => 'في الانتظار',
                        'verified' => 'مُتحقق',
                        'rejected' => 'مرفوض',
                    ]),
                Tables\Filters\SelectFilter::make('country')
                    ->label('البلد')
                    ->searchable()
                    ->options(function () {
                        return \App\Models\User::distinct('country')
                            ->whereNotNull('country')
                            ->pluck('country', 'country')
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد'),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
