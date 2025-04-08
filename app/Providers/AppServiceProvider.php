<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (!$this->app->environment('production')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('apiResponse', function ($status='errors', $message='An error occur. Try again', $data = null, $statusCode =500) {
            $response = [
                'status' => strtolower($status),
                'message' => $message,
            ];
        
            if (!is_null($data)) {
                $response['data'] = $data;
            }
        
            return response()->json($response, $statusCode);
        });
    }
}
