<?php

namespace App\Filament\Resources\AaResource\Pages;

use App\Filament\Resources\AaResource;
use Filament\Resources\Pages\Page;

class TestPage extends Page
{
    protected static string $resource = AaResource::class;

    protected static string $view = 'filament.resources.aa-resource.pages.test-page';
}
