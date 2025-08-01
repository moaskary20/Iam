<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SimpleTest extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.pages.simple-test';
    
    protected static ?string $title = 'اختبار بسيط';
    
    public function mount(): void
    {
        // Test method
    }
}
