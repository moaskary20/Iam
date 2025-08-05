<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'login',
        'login-simple',
        'logout',
        'logout-simple',
        'register',
        'livewire/*',
        'livewire/upload-file',
        'livewire/preview-file/*',
        'admin/upload-slider-image',
        'api/*',
    ];
}
