@extends('layouts.dashboard')
@section('content')
<div class="container">
    <h1 class="mt-4 mb-4">Product List</h1>
    <div class="dropdown">
            <a href="{{route('admin.dashboard') }}" class="btn btn-secondary"><i class="fa fa-angle-left"></i> Dashboard</a>
                <button type="button" class="btn btn-info dropdown-toggle mt-1" data-bs-toggle="dropdown">
                
                
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart <span class="badge badge-pill badge-danger">{{ count((array) session('cart')) }}</span>
                </button>

    <!-- Search Form -->


    <!-- Product List -->
    <div class="row">
        @if($products->count())
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ asset('upload/product_images/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            <p class="card-text text-success">${{ $product->price }}</p>
                            
                            <!-- Add to Cart Button -->
                            <a href="{{ route('add.to.cart', $product->id) }}" class="btn btn-primary">Add to Cart</a>

                            <!-- Edit Button -->
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>

                            <!-- Delete Button -->
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center">No products found.</p>
        @endif
    </div>
</div>
@endsection
