<?php

namespace App\Providers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts.topbar', 'layouts.sidebar'], function ($view) {
            $unreadCount = 0;
            $latestNotifications = collect();

            if (auth()->check()) {
                $query = Notifikasi::query()->where('user_id', auth()->id());
                $unreadCount = (clone $query)->belumDibaca()->count();
                $latestNotifications = $query->latest()->limit(5)->get();
            }

            $view->with(compact('unreadCount', 'latestNotifications'));
        });
    }
}