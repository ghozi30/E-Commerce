<?php

use App\Models\Category;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'utype' => 'ADM',
    ]);
});

test('admin can view categories list', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.categories'));

    $response->assertStatus(200);
    $response->assertSee('Categories');
});

test('admin can access add category page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.category.add'));

    $response->assertStatus(200);
    $response->assertSee('Add New Category');
});

test('admin can store a new category', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.category.store'), [
        'name' => 'Appliances',
        'slug' => 'appliances',
    ]);

    $response->assertRedirect(route('admin.categories'));
    $this->assertDatabaseHas('categories', [
        'name' => 'Appliances',
        'slug' => 'appliances',
    ]);
});

test('admin can update a category', function () {
    $category = Category::create([
        'name' => 'Old Category',
        'slug' => 'old-category',
    ]);

    $response = $this->actingAs($this->admin)->put(route('admin.category.update'), [
        'id' => $category->id,
        'name' => 'Updated Category',
        'slug' => 'updated-category',
    ]);

    $response->assertRedirect(route('admin.categories'));
    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Updated Category',
        'slug' => 'updated-category',
    ]);
});

test('admin can delete a category', function () {
    $category = Category::create([
        'name' => 'Category To Delete',
        'slug' => 'category-to-delete',
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.category.delete', ['id' => $category->id]));

    $response->assertRedirect(route('admin.categories'));
    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});
