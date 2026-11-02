<?php

use App\Facades\Cart;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    Cart::instance('cart')->destroy();
    Cart::instance('wishlist')->destroy();
    session()->forget(['coupon', 'discounts', 'order_id']);

    $this->admin = User::factory()->create([
        'utype' => 'ADM',
    ]);

    $this->customer = User::factory()->create([
        'utype' => 'USR',
    ]);

    $this->category = Category::firstOrCreate(['slug' => 'checkout-cat'], ['name' => 'Checkout Cat']);
    $this->brand = Brand::firstOrCreate(['slug' => 'checkout-brand'], ['name' => 'Checkout Brand']);
    $this->product = Product::firstOrCreate(['slug' => 'checkout-product'], [
        'name' => 'Checkout Test Product',
        'regular_price' => 100.00,
        'SKU' => 'CHK-100',
        'stock_status' => 'instock',
        'quantity' => 20,
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
    ]);
});

test('admin can manage coupons CRUD', function () {
    // 1. List
    $response = $this->actingAs($this->admin)->get(route('admin.coupons'));
    $response->assertStatus(200);

    // 2. Add
    $createResponse = $this->actingAs($this->admin)->post(route('admin.coupon.store'), [
        'code' => 'TEST10',
        'type' => 'fixed',
        'value' => 10,
        'cart_value' => 50,
        'expiry_date' => Carbon::tomorrow()->toDateString(),
    ]);
    $createResponse->assertRedirect(route('admin.coupons'));
    $this->assertDatabaseHas('coupons', ['code' => 'TEST10']);

    // 3. Edit & Update
    $coupon = Coupon::where('code', 'TEST10')->first();
    $editPage = $this->actingAs($this->admin)->get(route('admin.coupon.edit', ['id' => $coupon->id]));
    $editPage->assertStatus(200);

    $updateResponse = $this->actingAs($this->admin)->put(route('admin.coupon.update'), [
        'id' => $coupon->id,
        'code' => 'TEST20',
        'type' => 'percent',
        'value' => 20,
        'cart_value' => 60,
        'expiry_date' => Carbon::tomorrow()->toDateString(),
    ]);
    $updateResponse->assertRedirect(route('admin.coupons'));
    $this->assertDatabaseHas('coupons', ['code' => 'TEST20']);

    // 4. Delete
    $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.coupon.delete', ['id' => $coupon->id]));
    $deleteResponse->assertRedirect(route('admin.coupons'));
    $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
});

test('user can apply and remove coupon to cart', function () {
    $coupon = Coupon::create([
        'code' => 'SAVE10',
        'type' => 'percent',
        'value' => 10,
        'cart_value' => 50,
        'expiry_date' => Carbon::tomorrow()->toDateString(),
    ]);

    // Add product to cart ($100)
    Cart::instance('cart')->add($this->product->id, $this->product->name, 1, 100.00);

    // Apply coupon
    $response = $this->post(route('cart.apply.coupon'), ['coupon_code' => 'SAVE10']);
    $response->assertSessionHas('coupon');
    $response->assertSessionHas('discounts');

    $discounts = session('discounts');
    expect((float) $discounts['discount'])->toBe(10.00);
    expect((float) $discounts['subtotal'])->toBe(90.00);

    // Remove coupon
    $removeResponse = $this->delete(route('cart.remove.coupon'));
    $removeResponse->assertSessionMissing('coupon');
    $removeResponse->assertSessionMissing('discounts');
});

test('guest cannot checkout and is redirected to login', function () {
    Cart::instance('cart')->add($this->product->id, $this->product->name, 1, 100.00);

    $response = $this->get(route('cart.checkout'));
    $response->assertRedirect(route('login'));
});

test('authenticated user can view checkout page and place an order', function () {
    // Add product to cart ($100 x 2 = $200)
    Cart::instance('cart')->add($this->product->id, $this->product->name, 2, 100.00);

    // Access checkout
    $checkoutResponse = $this->actingAs($this->customer)->get(route('cart.checkout'));
    $checkoutResponse->assertStatus(200);
    $checkoutResponse->assertSee('Checkout Test Product');

    // Place order
    $orderResponse = $this->actingAs($this->customer)->post(route('cart.place.an.order'), [
        'name' => 'John Buyer',
        'phone' => '081234567890',
        'zip' => '12345',
        'state' => 'DKI Jakarta',
        'city' => 'Jakarta Selatan',
        'address' => 'Jl. Sudirman No. 45',
        'locality' => 'Kebayoran Baru',
        'landmark' => 'Near MRT Station',
        'mode' => 'cod',
    ]);

    $orderResponse->assertRedirect(route('cart.order.confirmation'));

    // Verify DB
    $order = Order::where('user_id', $this->customer->id)->latest()->first();
    expect($order)->not->toBeNull();
    expect((float) $order->subtotal)->toBe(200.00);
    expect($order->name)->toBe('John Buyer');
    expect($order->status)->toBe('ordered');

    // Verify relations
    expect($order->orderItems()->count())->toBe(1);
    expect($order->transaction)->not->toBeNull();
    expect($order->transaction->mode)->toBe('cod');
    expect($order->transaction->status)->toBe('pending');

    // Verify address saved
    $this->assertDatabaseHas('addresses', [
        'user_id' => $this->customer->id,
        'city' => 'Jakarta Selatan',
    ]);

    // Verify Cart is cleared
    expect(Cart::instance('cart')->count())->toBe(0);

    // Verify confirmation view
    $confirmResponse = $this->actingAs($this->customer)->get(route('cart.order.confirmation'));
    $confirmResponse->assertStatus(200);
    $confirmResponse->assertSee('Thank You for Your Order!');
    $confirmResponse->assertSee('John Buyer');
});
