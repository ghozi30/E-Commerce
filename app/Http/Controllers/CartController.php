<?php

namespace App\Http\Controllers;

use App\Facades\Cart;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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

        if (Session::has('coupon')) {
            $this->calculateDiscount();
        }

        return redirect()->route('cart.index')->with('status', 'Product has been added to your cart!');
    }

    public function increase_cart_quantity(string $rowId): RedirectResponse
    {
        $product = Cart::instance('cart')->get($rowId);
        if ($product) {
            $qty = $product->qty + 1;
            Cart::instance('cart')->update($rowId, $qty);
        }

        if (Session::has('coupon')) {
            $this->calculateDiscount();
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

        if (Session::has('coupon')) {
            $this->calculateDiscount();
        }

        return redirect()->back();
    }

    public function remove_item(string $rowId): RedirectResponse
    {
        Cart::instance('cart')->remove($rowId);

        if (Session::has('coupon')) {
            $this->calculateDiscount();
        }

        return redirect()->back()->with('status', 'Item has been removed from cart!');
    }

    public function empty_cart(): RedirectResponse
    {
        Cart::instance('cart')->destroy();
        Session::forget('coupon');
        Session::forget('discounts');

        return redirect()->back()->with('status', 'Cart has been cleared!');
    }

    // Coupon Methods
    public function apply_coupon_code(Request $request): RedirectResponse
    {
        $request->validate([
            'coupon_code' => 'required',
        ]);

        $code = strtoupper(trim((string) $request->coupon_code));
        $subtotal = Cart::instance('cart')->subtotal();

        $coupon = Coupon::where('code', $code)
            ->where('expiry_date', '>=', Carbon::today()->toDateString())
            ->where('cart_value', '<=', $subtotal)
            ->first();

        if (! $coupon) {
            return redirect()->back()->with('error', 'Invalid or expired coupon code, or minimum cart value not met!');
        }

        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => (float) $coupon->value,
            'cart_value' => (float) $coupon->cart_value,
        ]);

        $this->calculateDiscount();

        return redirect()->back()->with('status', 'Coupon applied successfully!');
    }

    public function remove_coupon_code(): RedirectResponse
    {
        Session::forget('coupon');
        Session::forget('discounts');

        return redirect()->back()->with('status', 'Coupon has been removed!');
    }

    public function calculateDiscount(): void
    {
        $subtotal = Cart::instance('cart')->subtotal();

        if (! Session::has('coupon') || $subtotal <= 0) {
            Session::forget('coupon');
            Session::forget('discounts');

            return;
        }

        $coupon = Session::get('coupon');

        // Check if cart subtotal still satisfies coupon minimum
        if ($subtotal < $coupon['cart_value']) {
            Session::forget('coupon');
            Session::forget('discounts');

            return;
        }

        if ($coupon['type'] === 'fixed') {
            $discount = (float) $coupon['value'];
        } else {
            $discount = round(($subtotal * (float) $coupon['value']) / 100, 2);
        }

        $subtotalAfterDiscount = max(0, $subtotal - $discount);
        $taxAfterDiscount = round($subtotalAfterDiscount * 0.1, 2);
        $totalAfterDiscount = round($subtotalAfterDiscount + $taxAfterDiscount, 2);

        Session::put('discounts', [
            'discount' => number_format($discount, 2, '.', ''),
            'subtotal' => number_format($subtotalAfterDiscount, 2, '.', ''),
            'tax' => number_format($taxAfterDiscount, 2, '.', ''),
            'total' => number_format($totalAfterDiscount, 2, '.', ''),
        ]);
    }

    // Checkout & Order Methods
    public function checkout(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (Cart::instance('cart')->content()->count() === 0) {
            return redirect()->route('cart.index');
        }

        $address = Address::where('user_id', Auth::id())->where('isdefault', true)->first();
        if (! $address) {
            $address = Address::where('user_id', Auth::id())->first();
        }

        $items = Cart::instance('cart')->content();

        return view('checkout', compact('address', 'items'));
    }

    public function place_an_order(Request $request): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'zip' => 'required|string|max:10',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'address' => 'required|string',
            'locality' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'mode' => 'required|in:cod,card,paypal',
        ]);

        $userId = Auth::id();

        // Save or update address
        $address = Address::where('user_id', $userId)->where('isdefault', true)->first();
        if (! $address) {
            $address = new Address;
            $address->user_id = $userId;
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark ?? '';
            $address->country = 'Indonesia';
            $address->isdefault = true;
            $address->save();
        }

        // Totals
        if (Session::has('discounts')) {
            $discounts = Session::get('discounts');
            $subtotal = (float) $discounts['subtotal'];
            $discount = (float) $discounts['discount'];
            $tax = (float) $discounts['tax'];
            $total = (float) $discounts['total'];
        } else {
            $subtotal = Cart::instance('cart')->subtotal();
            $discount = 0.00;
            $tax = Cart::instance('cart')->tax();
            $total = Cart::instance('cart')->total();
        }

        $order = new Order;
        $order->user_id = $userId;
        $order->subtotal = $subtotal;
        $order->discount = $discount;
        $order->tax = $tax;
        $order->total = $total;
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->locality = $request->locality;
        $order->address = $request->address;
        $order->city = $request->city;
        $order->state = $request->state;
        $order->country = 'Indonesia';
        $order->landmark = $request->landmark ?? '';
        $order->zip = $request->zip;
        $order->status = 'ordered';
        $order->is_shipping_different = false;
        $order->save();

        foreach (Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem;
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }

        $transaction = new Transaction;
        $transaction->user_id = $userId;
        $transaction->order_id = $order->id;
        $transaction->mode = $request->mode;
        $transaction->status = ($request->mode === 'cod') ? 'pending' : 'approved';
        $transaction->save();

        Cart::instance('cart')->destroy();
        Session::forget('coupon');
        Session::forget('discounts');
        Session::put('order_id', $order->id);

        return redirect()->route('cart.order.confirmation');
    }

    public function order_confirmation(): View|RedirectResponse
    {
        if (! Session::has('order_id')) {
            return redirect()->route('cart.index');
        }

        $order = Order::with(['orderItems.product', 'transaction'])->find(Session::get('order_id'));
        if (! $order) {
            return redirect()->route('cart.index');
        }

        return view('order-placed-confirmation', compact('order'));
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
