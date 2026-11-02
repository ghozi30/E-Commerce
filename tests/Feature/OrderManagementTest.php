<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'utype' => 'ADM',
    ]);

    $this->customer = User::factory()->create([
        'utype' => 'USR',
    ]);

    $this->otherCustomer = User::factory()->create([
        'utype' => 'USR',
    ]);

    $this->category = Category::firstOrCreate(['slug' => 'order-test-cat'], ['name' => 'Order Test Cat']);
    $this->brand = Brand::firstOrCreate(['slug' => 'order-test-brand'], ['name' => 'Order Test Brand']);
    $this->product = Product::firstOrCreate(['slug' => 'order-test-prod'], [
        'name' => 'Order Test Product',
        'regular_price' => 150.00,
        'SKU' => 'ORD-150',
        'stock_status' => 'instock',
        'quantity' => 15,
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
    ]);

    // Create an order for $this->customer
    $this->order = Order::create([
        'user_id' => $this->customer->id,
        'subtotal' => 150.00,
        'discount' => 0.00,
        'tax' => 15.00,
        'total' => 165.00,
        'name' => 'Customer Order User',
        'phone' => '081234567890',
        'locality' => 'Test Locality',
        'address' => 'Test Street No. 1',
        'city' => 'Jakarta',
        'state' => 'DKI Jakarta',
        'country' => 'Indonesia',
        'zip' => '12345',
        'status' => 'ordered',
        'is_shipping_different' => false,
    ]);

    OrderItem::create([
        'product_id' => $this->product->id,
        'order_id' => $this->order->id,
        'price' => 150.00,
        'quantity' => 1,
    ]);

    Transaction::create([
        'user_id' => $this->customer->id,
        'order_id' => $this->order->id,
        'mode' => 'cod',
        'status' => 'pending',
    ]);
});

test('admin can view orders list', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.orders'));
    $response->assertStatus(200);
    $response->assertSee('Customer Order User');
    $response->assertSee('$165.00');
});

test('admin can view single order details', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.order.details', ['order_id' => $this->order->id]));
    $response->assertStatus(200);
    $response->assertSee('Order Test Product');
    $response->assertSee('Test Street No. 1');
    $response->assertSee('Update Order Status');
});

test('admin can update order status to delivered', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.order.status.update'), [
        'order_id' => $this->order->id,
        'order_status' => 'delivered',
    ]);

    $response->assertRedirect();
    $this->order->refresh();
    expect($this->order->status)->toBe('delivered');
    expect($this->order->delivered_date)->not->toBeNull();

    $transaction = Transaction::where('order_id', $this->order->id)->first();
    expect($transaction->status)->toBe('approved');
});

test('admin can update order status to canceled', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.order.status.update'), [
        'order_id' => $this->order->id,
        'order_status' => 'canceled',
    ]);

    $response->assertRedirect();
    $this->order->refresh();
    expect($this->order->status)->toBe('canceled');
    expect($this->order->canceled_date)->not->toBeNull();
});

test('user can view their own orders', function () {
    $response = $this->actingAs($this->customer)->get(route('user.orders'));
    $response->assertStatus(200);
    $response->assertSee('#ORD-'.str_pad($this->order->id, 5, '0', STR_PAD_LEFT));
    $response->assertSee('$165.00');
});

test('user can view their own order details', function () {
    $response = $this->actingAs($this->customer)->get(route('user.order.details', ['order_id' => $this->order->id]));
    $response->assertStatus(200);
    $response->assertSee('Order Test Product');
    $response->assertSee('Cancel Order');
});

test('user cannot view another users order details', function () {
    $response = $this->actingAs($this->otherCustomer)->get(route('user.order.details', ['order_id' => $this->order->id]));
    $response->assertStatus(404);
});

test('user can cancel their ordered order', function () {
    $response = $this->actingAs($this->customer)->put(route('user.order.cancel'), [
        'order_id' => $this->order->id,
    ]);

    $response->assertRedirect();
    $this->order->refresh();
    expect($this->order->status)->toBe('canceled');
    expect($this->order->canceled_date)->not->toBeNull();
});
