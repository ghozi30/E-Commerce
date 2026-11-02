<?php

namespace App\Http\Controllers;

use App\Facades\Cart;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $items = Cart::instance('cart')->content();

        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request): RedirectResponse
    {
        $product = Product::findOrFail($request->id);
        $price = $product->sale_price ? $product->sale_price : $product->regular_price;
        $qty = $request->quantity ? (int) $request->quantity : 1;

        Cart::instance('cart')->add($product->id, $product->name, $qty, $price)->associate(Product::class);

        return redirect()->route('cart.index')->with('status', 'Product has been added to your cart!');
    }

    public function increase_cart_quantity(string $rowId): RedirectResponse
    {
        $product = Cart::instance('cart')->get($rowId);
        if ($product) {
            $qty = $product->qty + 1;
            Cart::instance('cart')->update($rowId, $qty);
        }

        return redirect()->back();
    }

    public function decrease_cart_quantity(string $rowId): RedirectResponse
    {
        $product = Cart::instance('cart')->get($rowId);
        if ($product) {
            $qty = $product->qty - 1;
            Cart::instance('cart')->update($rowId, $qty);
        }

        return redirect()->back();
    }

    public function remove_item(string $rowId): RedirectResponse
    {
        Cart::instance('cart')->remove($rowId);

        return redirect()->back()->with('status', 'Item has been removed from cart!');
    }

    public function empty_cart(): RedirectResponse
    {
        Cart::instance('cart')->destroy();

        return redirect()->back()->with('status', 'Cart has been cleared!');
    }

    // Wishlist Methods
    public function wishlist(): View
    {
        $items = Cart::instance('wishlist')->content();

        return view('wishlist', compact('items'));
    }

    public function add_to_wishlist(Request $request): RedirectResponse
    {
        $product = Product::findOrFail($request->id);
        $price = $product->sale_price ? $product->sale_price : $product->regular_price;

        Cart::instance('wishlist')->add($product->id, $product->name, 1, $price)->associate(Product::class);

        return redirect()->back()->with('status', 'Product has been added to your wishlist!');
    }

    public function remove_item_from_wishlist(string $rowId): RedirectResponse
    {
        Cart::instance('wishlist')->remove($rowId);

        return redirect()->back()->with('status', 'Item removed from wishlist!');
    }

    public function empty_wishlist(): RedirectResponse
    {
        Cart::instance('wishlist')->destroy();

        return redirect()->back()->with('status', 'Wishlist has been cleared!');
    }

    public function move_to_cart(string $rowId): RedirectResponse
    {
        $item = Cart::instance('wishlist')->get($rowId);
        if ($item) {
            Cart::instance('wishlist')->remove($rowId);
            Cart::instance('cart')->add($item->id, $item->name, 1, $item->price)->associate(Product::class);
        }

        return redirect()->route('cart.index')->with('status', 'Item moved to cart!');
    }
}
