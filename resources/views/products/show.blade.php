<!DOCTYPE html>
<html>
<head>
    <title>Product Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <a href="{{ route('products.list') }}" class="btn btn-secondary mb-3">⬅ Back</a>

    <div class="card shadow-lg p-4">
        <h2 class="mb-4">{{ $product->name }}</h2>

        <div class="mb-3">
            <strong>Price:</strong> ₹{{ $product->price }}
        </div>

        <div class="mb-4">
            <strong>Description:</strong>
            <p>{{ $product->description ?? 'No description available' }}</p>
        </div>

        <div class="mb-3">
            <strong>Created At:</strong> {{ $product->created_at->format('M d, Y H:i A') }}
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('products.delete', $product->id) }}" method="POST" style="display:inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
