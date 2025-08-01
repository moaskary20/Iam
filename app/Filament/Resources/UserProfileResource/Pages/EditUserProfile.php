<?php

namespace App\Filament\Resources\UserProfileResource\Pages;

use App\Filament\Resources\UserProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserProfile extends EditRecord
{
    protected static string $resource = UserProfileResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $original = $this->record->getOriginal('is_verified');
        if (
            array_key_exists('is_verified', $data)
            && !$original
            && $data['is_verified']
        ) {
            // Send notification to user (implement notification logic here)
            // Example: $this->record->notify(new \App\Notifications\UserVerifiedNotification());
        }

        // معالجة كلمة المرور: إذا كانت فارغة لا تحدثها، إذا كانت غير فارغة اعمل لها hash
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        }
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
