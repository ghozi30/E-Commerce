<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'utype' => 'ADM',
    ]);
    $this->category = Category::firstOrCreate(['slug' => 'test-cat'], ['name' => 'Test Cat']);
    $this->brand = Brand::firstOrCreate(['slug' => 'test-brand'], ['name' => 'Test Brand']);
});

test('admin can view products list', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.products'));

    $response->assertStatus(200);
    $response->assertSee('Products');
});

test('admin can access add product page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.product.add'));

    $response->assertStatus(200);
    $response->assertSee('Add New Product');
});

test('admin can store a new product', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.product.store'), [
        'name' => 'Mechanical Keyboard',
        'slug' => 'mechanical-keyboard',
        'regular_price' => 89.99,
        'SKU' => 'KB-001',
        'stock_status' => 'instock',
        'quantity' => 20,
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
    ]);

    $response->assertRedirect(route('admin.products'));
    $this->assertDatabaseHas('products', [
        'name' => 'Mechanical Keyboard',
        'SKU' => 'KB-001',
    ]);
});

test('admin can update a product', function () {
    $product = Product::create([
        'name' => 'Old Product',
        'slug' => 'old-product',
        'regular_price' => 50.00,
        'SKU' => 'OLD-001',
        'stock_status' => 'instock',
        'quantity' => 5,
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
    ]);

    $response = $this->actingAs($this->admin)->put(route('admin.product.update'), [
        'id' => $product->id,
        'name' => 'Updated Product',
        'slug' => 'updated-product',
        'regular_price' => 75.00,
        'SKU' => 'UPD-001',
        'stock_status' => 'instock',
        'quantity' => 8,
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
    ]);

    $response->assertRedirect(route('admin.products'));
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated Product',
        'SKU' => 'UPD-001',
    ]);
});

test('admin can delete a product', function () {
    $product = Product::create([
        'name' => 'Product To Delete',
        'slug' => 'product-to-delete',
        'regular_price' => 30.00,
        'SKU' => 'DEL-001',
        'stock_status' => 'instock',
        'quantity' => 10,
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.product.delete', ['id' => $product->id]));

    $response->assertRedirect(route('admin.products'));
    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});
