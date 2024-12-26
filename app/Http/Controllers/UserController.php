<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Ensure you import the Product model

class UserController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function userDashboard()
    {
        return view("user.user_dashboard");
    }

    /**
     * Display a listing of products with optional search functionality.
     */
    public function views(Request $request)
{
    $search = $request->input('search');
    $products = $search
        ? Product::where('name', 'LIKE', "%$search%")
            ->orWhere('description', 'LIKE', "%$search%")
            ->get()
        : Product::all();

    return view('user.product', compact('products', 'search'));
}


    /**
     * Add a product to the cart.
     */
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Show the shopping cart.
     */
    public function cart()
    {
        return view('user.cart'); // Points to resources/views/cart.blade.php
    }

    /**
     * Checkout and clear the cart.
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        $totalPrice = 0;

        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => "Checkout successful.",
            'totalPrice' => $totalPrice
        ]);
    }
    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
            session()->flash('success', 'Product removed successfully');
        }
    }

}
