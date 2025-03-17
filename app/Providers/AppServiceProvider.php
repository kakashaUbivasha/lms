<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\CourseService;
use App\Services\LessonService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService();
        });
        $this->app->singleton(LessonService::class, function ($app){
            return new LessonService();
        });
        $this->app->singleton(CourseService::class, function ($app){
            return new CourseService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
