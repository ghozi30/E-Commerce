<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
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
        $totalSales = Order::where('status', '!=', 'canceled')->sum('total');
        $totalOrders = Order::count();
        $totalCustomers = User::where('utype', 'USR')->count();
        $totalProducts = Product::count();
        $recentOrders = Order::with('user', 'transaction')->orderBy('id', 'DESC')->take(5)->get();

        return view('admin.index', compact('totalSales', 'totalOrders', 'totalCustomers', 'totalProducts', 'recentOrders'));
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

    public function categories(): View
    {
        $categories = Category::with('parent')->orderBy('id', 'DESC')->paginate(10);

        return view('admin.categories', compact('categories'));
    }

    public function add_category(): View
    {
        $parents = Category::whereNull('parent_id')->get();

        return view('admin.category-add', compact('parents'));
    }

    public function category_store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug ?: $request->name);
        $category->parent_id = $request->parent_id ?: null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $image->move(public_path('uploads/categories'), $file_name);
            $category->image = $file_name;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('status', 'Category has been added successfully!');
    }

    public function category_edit(int $id): View
    {
        $category = Category::findOrFail($id);
        $parents = Category::whereNull('parent_id')->where('id', '!=', $id)->get();

        return view('admin.category-edit', compact('category', 'parents'));
    }

    public function category_update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$request->id,
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = Category::findOrFail($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug ?: $request->name);
        $category->parent_id = $request->parent_id ?: null;

        if ($request->hasFile('image')) {
            if ($category->image && File::exists(public_path('uploads/categories/'.$category->image))) {
                File::delete(public_path('uploads/categories/'.$category->image));
            }

            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $image->move(public_path('uploads/categories'), $file_name);
            $category->image = $file_name;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('status', 'Category has been updated successfully!');
    }

    public function category_delete(int $id): RedirectResponse
    {
        $category = Category::findOrFail($id);

        if ($category->image && File::exists(public_path('uploads/categories/'.$category->image))) {
            File::delete(public_path('uploads/categories/'.$category->image));
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('status', 'Category has been deleted successfully!');
    }

    public function products(): View
    {
        $products = Product::with(['category', 'brand'])->orderBy('id', 'DESC')->paginate(10);

        return view('admin.products', compact('products'));
    }

    public function add_product(): View
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();

        return view('admin.product-add', compact('categories', 'brands'));
    }

    public function product_store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'nullable',
            'description' => 'nullable',
            'regular_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'SKU' => 'required|unique:products,SKU',
            'stock_status' => 'required|in:instock,outofstock',
            'featured' => 'nullable|boolean',
            'quantity' => 'required|numeric',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048',
            'images.*' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->slug = Str::slug($request->slug ?: $request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price ?: null;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->has('featured') ? 1 : 0;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $image->move(public_path('uploads/products'), $file_name);
            $product->image = $file_name;
        }

        if ($request->hasFile('images')) {
            $gallery_images = [];
            $counter = 1;
            foreach ($request->file('images') as $file) {
                $file_extension = $file->extension();
                $file_name = Carbon::now()->timestamp.'-'.$counter.'.'.$file_extension;
                $file->move(public_path('uploads/products'), $file_name);
                $gallery_images[] = $file_name;
                $counter++;
            }
            $product->images = implode(',', $gallery_images);
        }

        $product->save();

        return redirect()->route('admin.products')->with('status', 'Product has been added successfully!');
    }

    public function product_edit(int $id): View
    {
        $product = Product::findOrFail($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();

        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function product_update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,'.$request->id,
            'short_description' => 'nullable',
            'description' => 'nullable',
            'regular_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'SKU' => 'required|unique:products,SKU,'.$request->id,
            'stock_status' => 'required|in:instock,outofstock',
            'featured' => 'nullable|boolean',
            'quantity' => 'required|numeric',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048',
            'images.*' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $product = Product::findOrFail($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->slug ?: $request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price ?: null;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->has('featured') ? 1 : 0;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path('uploads/products/'.$product->image))) {
                File::delete(public_path('uploads/products/'.$product->image));
            }

            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $image->move(public_path('uploads/products'), $file_name);
            $product->image = $file_name;
        }

        if ($request->hasFile('images')) {
            if ($product->images) {
                foreach (explode(',', $product->images) as $old_img) {
                    if (File::exists(public_path('uploads/products/'.trim($old_img)))) {
                        File::delete(public_path('uploads/products/'.trim($old_img)));
                    }
                }
            }

            $gallery_images = [];
            $counter = 1;
            foreach ($request->file('images') as $file) {
                $file_extension = $file->extension();
                $file_name = Carbon::now()->timestamp.'-'.$counter.'.'.$file_extension;
                $file->move(public_path('uploads/products'), $file_name);
                $gallery_images[] = $file_name;
                $counter++;
            }
            $product->images = implode(',', $gallery_images);
        }

        $product->save();

        return redirect()->route('admin.products')->with('status', 'Product has been updated successfully!');
    }

    public function product_delete(int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        if ($product->image && File::exists(public_path('uploads/products/'.$product->image))) {
            File::delete(public_path('uploads/products/'.$product->image));
        }

        if ($product->images) {
            foreach (explode(',', $product->images) as $img) {
                if (File::exists(public_path('uploads/products/'.trim($img)))) {
                    File::delete(public_path('uploads/products/'.trim($img)));
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully!');
    }

    // Coupons
    public function coupons(): View
    {
        $coupons = Coupon::orderBy('expiry_date', 'DESC')->paginate(10);

        return view('admin.coupons', compact('coupons'));
    }

    public function add_coupon(): View
    {
        return view('admin.coupon-add');
    }

    public function coupon_store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date',
        ]);

        $coupon = new Coupon;
        $coupon->code = strtoupper((string) $request->code);
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('status', 'Coupon has been added successfully!');
    }

    public function coupon_edit(int $id): View
    {
        $coupon = Coupon::findOrFail($id);

        return view('admin.coupon-edit', compact('coupon'));
    }

    public function coupon_update(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,'.$request->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date',
        ]);

        $coupon = Coupon::findOrFail($request->id);
        $coupon->code = strtoupper((string) $request->code);
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('status', 'Coupon has been updated successfully!');
    }

    public function coupon_delete(int $id): RedirectResponse
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupons')->with('status', 'Coupon has been deleted successfully!');
    }

    // Orders
    public function orders(): View
    {
        $orders = Order::with('user', 'transaction')->orderBy('id', 'DESC')->paginate(12);

        return view('admin.orders', compact('orders'));
    }

    public function order_details(int $order_id): View
    {
        $order = Order::with(['orderItems.product', 'transaction', 'user'])->findOrFail($order_id);
        $orderItems = $order->orderItems()->paginate(10);
        $transaction = $order->transaction;

        return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
    }

    public function update_order_status(Request $request): RedirectResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'order_status' => 'required|in:ordered,delivered,canceled',
        ]);

        $order = Order::findOrFail($request->order_id);
        $order->status = $request->order_status;

        if ($request->order_status === 'delivered') {
            $order->delivered_date = Carbon::now();
            if ($order->transaction) {
                $order->transaction->status = 'approved';
                $order->transaction->save();
            }
        } elseif ($request->order_status === 'canceled') {
            $order->canceled_date = Carbon::now();
            if ($order->transaction && $order->transaction->mode === 'card') {
                $order->transaction->status = 'refunded';
                $order->transaction->save();
            }
        }

        $order->save();

        return redirect()->back()->with('status', 'Order status updated successfully!');
    }
}
