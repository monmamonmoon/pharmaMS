<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//admin


Route::get('admin/views', [ProductController::class, 'views'])->name('admin.views');  
    Route::get('admin/cart', [ProductController::class, 'cart'])->name('cart');
    Route::get('add-to-cart/{id}', [ProductController::class, 'addToCart'])->name('add.to.cart');
    Route::patch('update-cart', [ProductController::class, 'update'])->name('update.cart');
    Route::delete('remove-from-cart', [ProductController::class, 'remove'])->name('remove.from.cart');
   Route::post('/checkout', [ProductController::class, 'checkout'])->name('checkout');


  Route::get('/products/product', [ProductController::class, 'views'])->name('products.product');
     Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
   Route::get('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
   Route::put('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/destroy/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [ProductController::class, 'store'])->name('products.store');

    

    //users

    Route::get('user/products', [UserController::class, 'views'])->name('user.products');

    // Add to cart route
    Route::get('cart/add/{id}', [UserController::class, 'addToCart'])->name('cart.add');
    Route::get('add-to-cart/{id}', [ProductController::class, 'addToCart'])->name('add.to.cart');
    Route::delete('remove-from-cart', [ProductController::class, 'remove'])->name('remove.from.cart');

    // Cart view route
    Route::get('user/cart', [UserController::class, 'cart'])->name('user.cart');

    // Checkout route
    Route::post('checkout', [UserController::class, 'checkout'])->name('checkout');

    
});

require __DIR__ . '/auth.php';

// Admin routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Dashboard route (GET)
    Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('dashboard');

    
    


});

// User routes
Route::middleware(['auth', 'roles:user'])->prefix('user')->group(function () {
    Route::get('dashboard', [UserController::class, 'UserDashboard'])->name('user.dashboard');

   
});
