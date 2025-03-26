<?php

public function boot(): void
{
    $this->configureRateLimiting();

    $this->routes(function () {
        // Debug: cek apakah file ada
        if (file_exists(base_path('routes/api.php'))) {
            \Log::info('File routes/api.php exists');
        } else {
            \Log::error('File routes/api.php not found');
        }

        try {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            \Log::info('Successfully loaded routes/api.php');
        } catch (\Exception $e) {
            \Log::error('Failed to load routes/api.php: ' . $e->getMessage());
        }

        Route::middleware('web')
            ->group(base_path('routes/web.php'));

         Route::middleware('api')
              ->prefix('api')
             ->group('C:/xampp/htdocs/recipe-app/routes/api.php');
    });
}