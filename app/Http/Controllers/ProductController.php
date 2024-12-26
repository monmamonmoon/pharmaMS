<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
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

        return view('products.product', compact('products', 'search')); // Points to resources/views/products.blade.php
    }

    /**
     * Show form to create a new product.
     */
    public function create()    
    {
        return view('products.create'); // Points to resources/views/products/create.blade.php
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('upload/product_images'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        return redirect()->route('admin.views')->with('success', 'Product created successfully!');
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
        return view('admin.cart'); // Points to resources/views/cart.blade.php
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

    public function updateCheckoutStatus(Request $request)
    {
        // Ensure the user is an admin (optional if the route is already middleware-protected)
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Set the session to indicate the checkout is completed
        Session::put('checkoutCompleted', true);

        return response()->json(['success' => true]);
    }


    /**
     * Update product quantity in the cart.
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$request->id])) {
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
    }
    /**
     * Remove product from the cart.
     */
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

    /**
     * Show the form for editing a product.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product')); // Points to resources/views/products/edit.blade.php
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        if ($request->hasFile('image')) {
            // Delete the old image
            if ($product->image && file_exists(public_path('upload/product_images/' . $product->image))) {
                unlink(public_path('upload/product_images/' . $product->image));
            }

            // Save the new image
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('upload/product_images'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        return redirect()->route('admin.views')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete the product's image
        if ($product->image && file_exists(public_path('upload/product_images/' . $product->image))) {
            unlink(public_path('upload/product_images/' . $product->image));
        }

        $product->delete();

        return redirect()->route('admin.views')->with('success', 'Product deleted successfully!');
    }
}
