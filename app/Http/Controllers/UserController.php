<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())->with('transaction')->orderBy('id', 'DESC')->take(5)->get();

        return view('users.index', compact('orders'));
    }

    public function orders(): View
    {
        $orders = Order::where('user_id', Auth::id())->with('transaction')->orderBy('id', 'DESC')->paginate(10);

        return view('users.orders', compact('orders'));
    }

    public function order_details(int $order_id): View
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $order_id)
            ->with(['orderItems.product', 'transaction'])
            ->firstOrFail();

        $orderItems = $order->orderItems()->paginate(10);
        $transaction = $order->transaction;

        return view('users.order-details', compact('order', 'orderItems', 'transaction'));
    }

    public function order_cancel(Request $request): RedirectResponse
    {
        $order = Order::where('user_id', Auth::id())->where('id', $request->order_id)->firstOrFail();
        $order->status = 'canceled';
        $order->canceled_date = Carbon::now();
        $order->save();

        return back()->with('status', 'Order has been canceled successfully!');
    }
}
