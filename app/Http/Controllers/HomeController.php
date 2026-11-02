<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function contact(): View
    {
        return view('contact');
    }

    public function contact_store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        $comment = $request->comment ?? $request->message ?? '';
        if (empty($comment)) {
            return redirect()->back()->withErrors(['comment' => 'Please provide a message.'])->withInput();
        }

        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->subject = $request->subject;
        $contact->comment = $comment;
        $contact->save();

        return redirect()->back()->with('status', 'Your message has been sent successfully! We will get back to you soon.');
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('name') ?? $request->input('query') ?? $request->input('q') ?? '';
        $query = trim((string) $query);

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->take(8)
            ->get(['id', 'name', 'slug', 'image', 'regular_price', 'sale_price']);

        return response()->json($products);
    }
}
