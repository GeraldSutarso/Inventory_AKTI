<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'head') {
                $notifications = Order::with('user', 'product')
                    ->where('kaunit_id', $user->id)
                    ->whereNull('is_approved')
                    ->whereNull('is_rejected')
                    ->latest()
                    ->get();
            } elseif ($user->role === 'sarpras') {
                $notifications = Order::with('user', 'product')
                    ->whereNull('is_acknowledged')
                    ->latest()
                    ->get();
            } else {
                $notifications = collect(); // Empty collection for other roles
            }

            $view->with('notifications', $notifications)
                 ->with('unreadCount', $notifications->count());
        }
    });
    }
}
