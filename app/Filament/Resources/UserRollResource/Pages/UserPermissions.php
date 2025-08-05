<?php

namespace App\Filament\Resources\UserRollResource\Pages;

use App\Filament\Resources\UserRollResource;
use Filament\Resources\Pages\Page;

class UserPermissions extends Page
{
    protected static string $resource = UserRollResource::class;

    protected static string $view = 'filament.resources.user-roll-resource.pages.user-permissions';
}
