<?php

namespace App\Facades;

use App\Services\CartService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\CartService instance(string $name = 'cart')
 * @method static \Illuminate\Support\Collection content()
 * @method static int count()
 * @method static \App\Services\CartItem add($id, $name, $qty, $price, array $options = [])
 * @method static void update(string $rowId, int $qty)
 * @method static void remove(string $rowId)
 * @method static void destroy()
 * @method static float subtotal()
 * @method static float tax()
 * @method static float total()
 *
 * @see CartService
 */
class Cart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cart';
    }
}
