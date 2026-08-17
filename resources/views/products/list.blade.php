<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>All Products</h2>
        <a href="{{ route('products.create') }}" class="btn btn-success">+ Add Product</a>
    </div>

    <form action="{{ route('products.search') }}" method="GET" class="d-flex mb-4">
        <input type="text" name="query" class="form-control me-2" placeholder="Search products...">
        <button class="btn btn-primary">Search</button>
    </form>

    <div class="card shadow p-3">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price (₹)</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->description }}</td>
                    <td><b>₹{{ $p->price }}</b></td>
                    <td class="text-center">
                        <a href="{{ route('products.show', $p->id) }}" class="btn btn-sm btn-info text-white">View</a>
                        <a href="{{ route('products.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('products.delete', $p->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

