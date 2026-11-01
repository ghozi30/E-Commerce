<?php

use App\Models\Brand;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'utype' => 'ADM',
    ]);
});

test('admin can view brands list', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.brands'));

    $response->assertStatus(200);
    $response->assertSee('Brands');
});

test('admin can access add brand page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.brand.add'));

    $response->assertStatus(200);
    $response->assertSee('Add New Brand');
});

test('admin can store a new brand', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.brand.store'), [
        'name' => 'Sony',
        'slug' => 'sony',
    ]);

    $response->assertRedirect(route('admin.brands'));
    $this->assertDatabaseHas('brands', [
        'name' => 'Sony',
        'slug' => 'sony',
    ]);
});

test('admin can update a brand', function () {
    $brand = Brand::create([
        'name' => 'Old Brand',
        'slug' => 'old-brand',
    ]);

    $response = $this->actingAs($this->admin)->put(route('admin.brand.update'), [
        'id' => $brand->id,
        'name' => 'Updated Brand',
        'slug' => 'updated-brand',
    ]);

    $response->assertRedirect(route('admin.brands'));
    $this->assertDatabaseHas('brands', [
        'id' => $brand->id,
        'name' => 'Updated Brand',
        'slug' => 'updated-brand',
    ]);
});

test('admin can delete a brand', function () {
    $brand = Brand::create([
        'name' => 'Brand To Delete',
        'slug' => 'brand-to-delete',
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.brand.delete', ['id' => $brand->id]));

    $response->assertRedirect(route('admin.brands'));
    $this->assertDatabaseMissing('brands', [
        'id' => $brand->id,
    ]);
});
