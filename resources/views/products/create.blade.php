@extends('admin.views')

@section('content')
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="name">Product Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" class="form-control" id="price" name="price" required>
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
    </div>
    <div class="form-group">
        <label>Image Preview:</label>
        <img id="image-preview" src="#" alt="Image Preview" style="max-width: 200px; display: none;" />
    </div>
    <button type="submit" class="btn btn-primary">Create Product</button>
</form>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('image-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
