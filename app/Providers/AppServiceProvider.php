<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Models\Payment;

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
        View::composer('customer.layouts.userLayout', function ($view) {
            $view->with('categories', Category::orderBy('name')->get());
        });

        View::composer('layouts.partials.header', function ($view) {
            $notifications = collect();
            $unreadCount = 0;
            $user = Auth::user();

            if ($user && in_array($user->role, ['admin', 'superadmin'], true)) {
                $cacheKey = 'payments_seen_ids_' . $user->id;
                $seenIds = collect(Cache::get($cacheKey, []))
                    ->filter(fn ($id) => is_numeric($id))
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->values();

                $notifications = Payment::with('user:id,first_name,last_name,email')
                    ->where('status', 'SUCCESS')
                    ->orderByDesc('paid_at')
                    ->orderByDesc('created_at')
                    ->limit(6)
                    ->get();

                $notifications->each(function ($payment) use ($seenIds) {
                    $payment->is_unread = ! $seenIds->contains((int) $payment->id);
                });

                $unreadCount = Payment::where('status', 'SUCCESS')
                    ->when($seenIds->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $seenIds))
                    ->count();
            }

            $view->with([
                'paymentNotifications' => $notifications,
                'paymentNotificationsUnreadCount' => $unreadCount,
            ]);
        });
    }
}
