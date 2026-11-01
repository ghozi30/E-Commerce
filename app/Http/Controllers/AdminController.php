<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.index');
    }

    public function brands(): View
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);

        return view('admin.brands', compact('brands'));
    }

    public function add_brand(): View
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $brand = new Brand;
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug ?: $request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $image->move(public_path('uploads/brands'), $file_name);
            $brand->image = $file_name;
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand has been added successfully!');
    }

    public function brand_edit(int $id): View
    {
        $brand = Brand::findOrFail($id);

        return view('admin.brand-edit', compact('brand'));
    }

    public function brand_update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$request->id,
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $brand = Brand::findOrFail($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug ?: $request->name);

        if ($request->hasFile('image')) {
            if ($brand->image && File::exists(public_path('uploads/brands/'.$brand->image))) {
                File::delete(public_path('uploads/brands/'.$brand->image));
            }

            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $image->move(public_path('uploads/brands'), $file_name);
            $brand->image = $file_name;
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand has been updated successfully!');
    }

    public function brand_delete(int $id): RedirectResponse
    {
        $brand = Brand::findOrFail($id);

        if ($brand->image && File::exists(public_path('uploads/brands/'.$brand->image))) {
            File::delete(public_path('uploads/brands/'.$brand->image));
        }

        $brand->delete();

        return redirect()->route('admin.brands')->with('status', 'Brand has been deleted successfully!');
    }
}
