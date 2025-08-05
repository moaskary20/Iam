<?php

namespace App\Filament\Resources\UserpermissionResource\Pages;

use App\Filament\Resources\UserpermissionResource;
use Filament\Resources\Pages\Page;

class UserPermissionsManager extends Page
{
    protected static string $resource = UserpermissionResource::class;

    protected static string $view = 'filament.resources.userpermission-resource.pages.user-permissions-manager';
}
