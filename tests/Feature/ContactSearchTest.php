<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'utype' => 'ADM',
    ]);

    $this->customer = User::factory()->create([
        'utype' => 'USR',
    ]);

    $this->category = Category::firstOrCreate(['slug' => 'search-cat'], ['name' => 'Search Cat']);
    $this->brand = Brand::firstOrCreate(['slug' => 'search-brand'], ['name' => 'Search Brand']);

    $this->product = Product::firstOrCreate(['slug' => 'wireless-headphones'], [
        'name' => 'Wireless Noise Canceling Headphones',
        'regular_price' => 199.99,
        'SKU' => 'HEAD-001',
        'stock_status' => 'instock',
        'quantity' => 10,
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
    ]);
});

test('guest can access contact us page', function () {
    $response = $this->get(route('home.contact'));
    $response->assertStatus(200);
    $response->assertSee('Contact Us');
    $response->assertSee('Get In Touch');
});

test('user can submit contact form successfully', function () {
    $response = $this->post(route('home.contact.store'), [
        'name' => 'Jane Visitor',
        'email' => 'jane@example.com',
        'phone' => '081299988877',
        'subject' => 'Product Inquiry',
        'comment' => 'Hello, I would like to know if you offer international shipping?',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('contacts', [
        'name' => 'Jane Visitor',
        'email' => 'jane@example.com',
        'subject' => 'Product Inquiry',
    ]);
});

test('admin can view contacts list and delete a contact message', function () {
    $contact = Contact::create([
        'name' => 'Test Inquirer',
        'email' => 'test@inquirer.com',
        'comment' => 'Sample test message',
    ]);

    // Admin view list
    $response = $this->actingAs($this->admin)->get(route('admin.contacts'));
    $response->assertStatus(200);
    $response->assertSee('Test Inquirer');
    $response->assertSee('Sample test message');

    // Admin delete
    $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.contact.delete', ['id' => $contact->id]));
    $deleteResponse->assertRedirect(route('admin.contacts'));
    $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
});

test('live search endpoint returns matched products as JSON', function () {
    $response = $this->getJson(route('home.search', ['name' => 'Headphones']));

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'name' => 'Wireless Noise Canceling Headphones',
        'slug' => 'wireless-headphones',
    ]);
});

test('live search endpoint returns empty array for empty query', function () {
    $response = $this->getJson(route('home.search', ['name' => '']));

    $response->assertStatus(200);
    $response->assertExactJson([]);
});
