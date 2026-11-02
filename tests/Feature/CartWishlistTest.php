<?php

use App\Facades\Cart;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

beforeEach(function () {
    Cart::instance('cart')->destroy();
    Cart::instance('wishlist')->destroy();

    $this->category = Category::firstOrCreate(['slug' => 'test-cart-cat'], ['name' => 'Cart Cat']);
    $this->brand = Brand::firstOrCreate(['slug' => 'test-cart-brand'], ['name' => 'Cart Brand']);
    $this->product = Product::firstOrCreate(['slug' => 'test-cart-product'], [
        'name' => 'Test Cart Product',
        'regular_price' => 50.00,
        'SKU' => 'CART-001',
        'stock_status' => 'instock',
        'quantity' => 10,
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
    ]);
});

test('guest can add product to cart and view cart', function () {
    $response = $this->post(route('cart.add'), [
        'id' => $this->product->id,
        'quantity' => 2,
    ]);

    $response->assertRedirect(route('cart.index'));
    expect(Cart::instance('cart')->count())->toBe(2);

    $cartView = $this->get(route('cart.index'));
    $cartView->assertStatus(200);
    $cartView->assertSee('Test Cart Product');
});

test('user can update cart item quantity', function () {
    $item = Cart::instance('cart')->add($this->product->id, $this->product->name, 1, 50.00);

    $this->put(route('cart.increase.qty', ['rowId' => $item->rowId]));
    expect(Cart::instance('cart')->count())->toBe(2);

    $this->put(route('cart.decrease.qty', ['rowId' => $item->rowId]));
    expect(Cart::instance('cart')->count())->toBe(1);
});

test('user can remove item from cart and empty cart', function () {
    $item = Cart::instance('cart')->add($this->product->id, $this->product->name, 1, 50.00);

    $this->delete(route('cart.remove', ['rowId' => $item->rowId]));
    expect(Cart::instance('cart')->count())->toBe(0);

    Cart::instance('cart')->add($this->product->id, $this->product->name, 3, 50.00);
    $this->delete(route('cart.empty'));
    expect(Cart::instance('cart')->count())->toBe(0);
});

test('user can add to wishlist and move to cart', function () {
    $response = $this->post(route('wishlist.add'), [
        'id' => $this->product->id,
    ]);

    expect(Cart::instance('wishlist')->count())->toBe(1);

    $wishlistView = $this->get(route('wishlist.index'));
    $wishlistView->assertStatus(200);
    $wishlistView->assertSee('Test Cart Product');

    $item = Cart::instance('wishlist')->content()->first();
    $this->post(route('wishlist.move.to.cart', ['rowId' => $item->rowId]));

    expect(Cart::instance('wishlist')->count())->toBe(0);
    expect(Cart::instance('cart')->count())->toBe(1);
});
