<?php

namespace App\Providers;

use App\Facades\Cart;
use App\Services\CartService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('cart', function () {
            return new CartService;
        });

        if (! class_exists('Cart')) {
            class_alias(Cart::class, 'Cart');
        }
        if (! class_exists('Surfsidemedia\Shoppingcart\Facades\Cart')) {
            class_alias(Cart::class, 'Surfsidemedia\Shoppingcart\Facades\Cart');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
