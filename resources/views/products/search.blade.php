<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <a href="{{ route('products.list') }}" class="btn btn-secondary mb-3">⬅ Back</a>

    <h2>Search Results for: <span class="text-primary">{{ $query }}</span></h2>

    <div class="card shadow p-3 mt-3">

        @forelse($products as $p)
            <div class="border rounded p-3 mb-3 bg-white">
                <h5>{{ $p->name }}</h5>
                <p>{{ $p->description }}</p>
                <h6><b>₹{{ $p->price }}</b></h6>
            </div>
        @empty
            <p class="text-danger">No Products Found</p>
        @endforelse

    </div>

</div>

</body>
</html>
