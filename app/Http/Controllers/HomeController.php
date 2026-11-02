<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('name', 'ASC')->get();
        $featured_products = Product::where('featured', 1)->take(8)->get();
        $latest_products = Product::orderBy('id', 'DESC')->take(8)->get();

        return view('index', compact('categories', 'featured_products', 'latest_products'));
    }
}
