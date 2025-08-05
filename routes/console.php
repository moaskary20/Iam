<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// تسجيل كومند اختبار تسجيل الدخول
use App\Console\Commands\TestAdminLogin;

Artisan::command('test:admin-login {email} {password}', function () {
    $command = new TestAdminLogin();
    $command->handle();
})->purpose('Test admin login functionality');
