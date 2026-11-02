<?php

use App\Models\Slide;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'utype' => 'ADM',
    ]);
});

test('admin can view slides list', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.slides'));
    $response->assertStatus(200);
    $response->assertSee('Home Slider');
});

test('admin can access add slide page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.slide.add'));
    $response->assertStatus(200);
    $response->assertSee('Add New Slide');
});

test('admin can store a new slide', function () {
    $file = UploadedFile::fake()->create('test_slide.png', 50, 'image/png');

    $response = $this->actingAs($this->admin)->post(route('admin.slide.store'), [
        'tagline' => 'Best Summer Deals',
        'title' => 'Comfort Furniture',
        'subtitle' => 'Modern lifestyle',
        'link' => '/shop',
        'status' => 1,
        'image' => $file,
    ]);

    $response->assertRedirect(route('admin.slides'));
    $this->assertDatabaseHas('slides', [
        'title' => 'Comfort Furniture',
        'tagline' => 'Best Summer Deals',
    ]);

    // Clean up uploaded test file
    $slide = Slide::where('title', 'Comfort Furniture')->first();
    if ($slide && $slide->image && File::exists(public_path('uploads/slides/'.$slide->image))) {
        File::delete(public_path('uploads/slides/'.$slide->image));
    }
});

test('admin can update a slide', function () {
    $slide = Slide::create([
        'tagline' => 'Old Tag',
        'title' => 'Old Title',
        'subtitle' => 'Old Subtitle',
        'link' => '/shop',
        'status' => 1,
        'image' => 'slider-item-1.png',
    ]);

    $response = $this->actingAs($this->admin)->put(route('admin.slide.update'), [
        'id' => $slide->id,
        'tagline' => 'New Tag',
        'title' => 'Updated Title',
        'subtitle' => 'Updated Subtitle',
        'link' => '/shop?tag=new',
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.slides'));
    $this->assertDatabaseHas('slides', [
        'id' => $slide->id,
        'title' => 'Updated Title',
        'tagline' => 'New Tag',
    ]);
});

test('admin can delete a slide', function () {
    $slide = Slide::create([
        'tagline' => 'To Delete',
        'title' => 'To Delete Title',
        'subtitle' => 'Subtitle',
        'link' => '/shop',
        'status' => 1,
        'image' => 'slider-item-3.png',
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.slide.delete', ['id' => $slide->id]));
    $response->assertRedirect(route('admin.slides'));
    $this->assertDatabaseMissing('slides', ['id' => $slide->id]);
});
