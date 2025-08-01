<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserProfileResource\Pages;
use App\Filament\Resources\UserProfileResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserProfileResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('first_name')
                ->label('الاسم الأول')
                ->required(),
            Forms\Components\TextInput::make('last_name')
                ->label('الاسم الأخير')
                ->required(),
            Forms\Components\TextInput::make('email')
                ->label('البريد الإلكتروني')
                ->email()
                ->required(),
            Forms\Components\TextInput::make('password')
                ->label('كلمة المرور')
                ->password()
                ->required(fn($record) => $record === null),
            Forms\Components\TextInput::make('phone')
                ->label('رقم الهاتف مع كود الدولة')
                ->tel(),
            Forms\Components\TextInput::make('country')
                ->label('الدولة'),
            Forms\Components\TextInput::make('invite_code')
                ->label('كود الدعوة للمشاركة'),
            Forms\Components\FileUpload::make('profile_photo')
                ->label('الصورة الشخصية')
                ->directory('users')
                ->image()
                ->avatar()
                ->columnSpanFull(),
            Forms\Components\FileUpload::make('id_image_path')
                ->label('صورة البطاقة')
                ->image(),
            Forms\Components\Select::make('verification_status')
                ->label('حالة التوثيق')
                ->options([
                    'pending' => 'قيد المراجعة',
                    'approved' => 'تم التوثيق',
                    'rejected' => 'مرفوض',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')->label('الصورة الشخصية'),
                Tables\Columns\ImageColumn::make('id_image_path')
                    ->label('صورة البطاقة')
                    ->circular()
                    ->size(40)
                    ->extraAttributes(['style' => 'cursor:pointer'])
                    ->url(fn($record) => $record->id_image_path ? asset('storage/' . $record->id_image_path) : null),
                Tables\Columns\TextColumn::make('first_name')->label('الاسم الأول')->searchable(),
                Tables\Columns\TextColumn::make('last_name')->label('الاسم الأخير')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('رقم الهاتف')->searchable(),
                Tables\Columns\TextColumn::make('country')->label('الدولة'),
                Tables\Columns\TextColumn::make('invite_code')->label('كود الدعوة'),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ التسجيل')->dateTime('Y-m-d H:i'),
                Tables\Columns\IconColumn::make('is_verified')->boolean()->label('موثق'),
                Tables\Columns\TextColumn::make('verification_status')->label('حالة التوثيق')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'secondary'
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('verification_status')
                    ->label('حالة التوثيق')
                    ->options([
                        'pending' => 'قيد المراجعة',
                        'approved' => 'تم التوثيق',
                        'rejected' => 'مرفوض',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn($record) => $record->is_active ? 'تعطيل' : 'تفعيل')
                    ->icon(fn($record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn($record) => $record->is_active ? 'danger' : 'success')
                    ->action(function ($record) {
                        $record->is_active = !$record->is_active;
                        $record->save();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\WalletTransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserProfiles::route('/'),
            'create' => Pages\CreateUserProfile::route('/create'),
            'view' => Pages\ViewUserProfile::route('/{record}'),
            'edit' => Pages\EditUserProfile::route('/{record}/edit'),
        ];
    }
}
