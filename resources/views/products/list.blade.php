<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        .search-wrapper {
            position: relative;
        }

        #searchSuggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            display: none;
        }

        .suggestion-item {
            display: block;
            padding: 12px 15px;
            text-decoration: none;
            color: #212529;
            border-bottom: 1px solid #eee;
        }

        .suggestion-item:hover {
            background-color: #f8f9fa;
        }

        .suggestion-name {
            font-weight: 600;
        }

        .suggestion-price {
            font-size: 14px;
            color: #198754;
        }

        .suggestion-description {
            font-size: 13px;
            color: #6c757d;
        }
    </style>
</head>

<body class="bg-light">

<div class="container mt-5">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>All Products</h2>

        <a
            href="{{ route('products.create') }}"
            class="btn btn-success"
        >
            + Add Product
        </a>
    </div>

    <!-- Live Search -->
    <div class="search-wrapper mb-4">

        <form
            action="{{ route('products.search') }}"
            method="GET"
            id="searchForm"
            class="d-flex"
        >

            <input
                type="text"
                name="query"
                id="searchInput"
                class="form-control me-2"
                placeholder="Search products..."
                autocomplete="off"
            >

            <button class="btn btn-primary">
                Search
            </button>

        </form>

        <div id="searchSuggestions"></div>

    </div>

    <!-- Product Table -->
    <div class="card shadow p-3">

        <table class="table table-striped align-middle">

            <thead class="table-dark">

                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price (₹)</th>
                    <th class="text-center">Actions</th>
                </tr>

            </thead>

            <tbody>

                @forelse($products as $p)

                    <tr>

                        <td>
                            <strong>{{ $p->name }}</strong>
                        </td>

                        <td>
                            {{ $p->description }}
                        </td>

                        <td>
                            <b>₹{{ number_format($p->price, 2) }}</b>
                        </td>

                        <td class="text-center">

                            <a
                                href="{{ route('products.show', $p->id) }}"
                                class="btn btn-sm btn-info text-white"
                            >
                                View
                            </a>

                            <a
                                href="{{ route('products.edit', $p->id) }}"
                                class="btn btn-sm btn-warning"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('products.delete', $p->id) }}"
                                method="POST"
                                style="display:inline-block"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this product?')"
                                >
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No products available.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $products->links() }}
        </div>

    </div>

</div>

<script>

    const searchInput = document.getElementById('searchInput');
    const suggestionsBox = document.getElementById('searchSuggestions');

    let searchTimer;

    searchInput.addEventListener('input', function () {

        const query = this.value.trim();

        clearTimeout(searchTimer);

        if (query.length < 2) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.style.display = 'none';
            return;
        }

        searchTimer = setTimeout(() => {

            fetch(
                `{{ route('products.suggestions') }}?query=${encodeURIComponent(query)}`
            )
            .then(response => response.json())
            .then(products => {

                suggestionsBox.innerHTML = '';

                if (products.length === 0) {

                    suggestionsBox.innerHTML = `
                        <div class="p-3 text-muted">
                            No products found.
                        </div>
                    `;

                    suggestionsBox.style.display = 'block';

                    return;
                }

                products.forEach(product => {

                    const item = document.createElement('a');

                    item.href = product.url;
                    item.className = 'suggestion-item';

                    item.innerHTML = `
                        <div class="suggestion-name">
                            ${product.name}
                        </div>

                        <div class="suggestion-description">
                            ${product.description ?? ''}
                        </div>

                        <div class="suggestion-price">
                            ₹${product.price}
                        </div>
                    `;

                    suggestionsBox.appendChild(item);
                });

                suggestionsBox.style.display = 'block';

            })
            .catch(error => {

                console.error('Search error:', error);

                suggestionsBox.innerHTML = '';

                suggestionsBox.style.display = 'none';
            });

        }, 300);

    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function (event) {

        if (
            !searchInput.contains(event.target) &&
            !suggestionsBox.contains(event.target)
        ) {
            suggestionsBox.style.display = 'none';
        }

    });

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>