<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/admin';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // إضافة مسارات Livewire مخصصة لحل مشكلة Method Not Allowed
            $this->mapLivewireRoutes();
        });
    }

    /**
     * Define custom Livewire routes to handle GET/POST methods
     */
    protected function mapLivewireRoutes(): void
    {
        // Override Livewire upload route to accept GET requests
        Route::match(['GET', 'POST'], '/livewire/upload-file', function (Request $request) {
            if ($request->isMethod('GET')) {
                return response()->json([
                    'message' => 'Upload endpoint ready',
                    'csrf_token' => csrf_token(),
                    'status' => 'ready'
                ], 200);
            }

            // For POST requests, handle the actual file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                if ($file && $file->isValid()) {
                    $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('livewire-tmp', $filename, 'public');
                    
                    return response()->json([
                        'success' => true,
                        'filename' => $filename,
                        'path' => $path,
                        'url' => asset('storage/' . $path)
                    ], 200);
                }
            }

            return response()->json(['error' => 'No file provided'], 400);
        })->middleware(['web']);

        // Override Livewire preview route
        Route::match(['GET', 'POST'], '/livewire/preview-file/{filename}', function ($filename) {
            $path = storage_path('app/public/livewire-tmp/' . $filename);
            
            if (file_exists($path)) {
                return response()->file($path);
            }
            
            return response()->json(['error' => 'File not found'], 404);
        })->middleware(['web']);
    }
}
