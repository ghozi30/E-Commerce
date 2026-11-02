<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

beforeEach(function () {
    $this->category = Category::firstOrCreate(['slug' => 'test-electronics'], ['name' => 'Electronics']);
    $this->brand = Brand::firstOrCreate(['slug' => 'test-brand-shop'], ['name' => 'Brand Shop']);
    $this->product = Product::firstOrCreate(['slug' => 'sample-headphone'], [
        'name' => 'Sample Headphone',
        'regular_price' => 99.00,
        'SKU' => 'SHP-001',
        'stock_status' => 'instock',
        'featured' => 1,
        'quantity' => 10,
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
    ]);
});

test('guest can access homepage with categories and products', function () {
    $response = $this->get(route('home.index'));

    $response->assertStatus(200);
    $response->assertSee('Sample Headphone');
    $response->assertSee('Electronics');
});

test('guest can access shop page', function () {
    $response = $this->get(route('shop.index'));

    $response->assertStatus(200);
    $response->assertSee('Shop Catalog');
    $response->assertSee('Sample Headphone');
});

test('guest can filter shop products by category and price', function () {
    $response = $this->get(route('shop.index', [
        'categories' => $this->category->id,
        'min' => 50,
        'max' => 150,
    ]));

    $response->assertStatus(200);
    $response->assertSee('Sample Headphone');
});

test('guest can view product details page', function () {
    $response = $this->get(route('shop.product.details', ['slug' => $this->product->slug]));

    $response->assertStatus(200);
    $response->assertSee('Sample Headphone');
    $response->assertSee('SHP-001');
    $response->assertSee('Add to Cart');
});
