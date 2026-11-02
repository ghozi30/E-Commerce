<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $size = $request->query('size') ? (int) $request->query('size') : 12;
        $order = $request->query('order') ? (int) $request->query('order') : -1;
        $f_brands = $request->query('brands');
        $f_categories = $request->query('categories');
        $min_price = $request->query('min') ? (float) $request->query('min') : 1;
        $max_price = $request->query('max') ? (float) $request->query('max') : 1000;

        $o_column = 'id';
        $o_order = 'DESC';

        switch ($order) {
            case 1:
                $o_column = 'created_at';
                $o_order = 'DESC';
                break;
            case 2:
                $o_column = 'created_at';
                $o_order = 'ASC';
                break;
            case 3:
                $o_column = 'regular_price';
                $o_order = 'ASC';
                break;
            case 4:
                $o_column = 'regular_price';
                $o_order = 'DESC';
                break;
            default:
                $o_column = 'id';
                $o_order = 'DESC';
                break;
        }

        $query = Product::query();

        if (! empty($f_brands)) {
            $brand_ids = is_array($f_brands) ? $f_brands : explode(',', $f_brands);
            $query->whereIn('brand_id', $brand_ids);
        }

        if (! empty($f_categories)) {
            $category_ids = is_array($f_categories) ? $f_categories : explode(',', $f_categories);
            $query->whereIn('category_id', $category_ids);
        }

        $query->where(function ($q) use ($min_price, $max_price) {
            $q->whereBetween('regular_price', [$min_price, $max_price])
                ->orWhereBetween('sale_price', [$min_price, $max_price]);
        });

        $products = $query->orderBy($o_column, $o_order)->paginate($size)->withQueryString();
        $categories = Category::withCount('products')->orderBy('name', 'ASC')->get();
        $brands = Brand::withCount('products')->orderBy('name', 'ASC')->get();

        return view('shop', compact(
            'products',
            'size',
            'order',
            'brands',
            'f_brands',
            'categories',
            'f_categories',
            'min_price',
            'max_price'
        ));
    }

    public function product_details(string $slug): View
    {
        $product = Product::with(['category', 'brand'])->where('slug', $slug)->firstOrFail();
        $rproducts = Product::where('slug', '!=', $slug)
            ->where('category_id', $product->category_id)
            ->take(8)
            ->get();

        return view('details', compact('product', 'rproducts'));
    }
}
