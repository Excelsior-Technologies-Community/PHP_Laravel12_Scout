<!DOCTYPE html>
<html>

<head>

    <title>Product Search</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        .search-history-card {
            min-height: 100%;
        }

        .search-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin: 4px;
            text-decoration: none;
        }

        .search-chip:hover {
            transform: translateY(-1px);
        }

        .popular-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .popular-item:last-child {
            border-bottom: none;
        }

        .rank-number {
            width: 28px;
            height: 28px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            background: #f1f3f5;
            margin-right: 8px;
            font-weight: 600;
        }

    </style>

</head>

<body class="bg-light">

<div class="container mt-5">

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            Product Search
        </h2>

        <a
            href="{{ route('products.list') }}"
            class="btn btn-secondary"
        >
            ← All Products
        </a>

    </div>


    <!-- Success Message -->

    @if (session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    <!-- Validation Errors -->

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- Search & Filters -->

    <div class="card shadow-sm p-4 mb-4">

        <form
            action="{{ route('products.search') }}"
            method="GET"
        >

            <div class="row g-3">

                <!-- Search -->

                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Search Product
                    </label>

                    <input
                        type="text"
                        name="query"
                        class="form-control"
                        value="{{ $query }}"
                        placeholder="Search by product name or description..."
                    >

                </div>


                <!-- Minimum Price -->

                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Minimum Price
                    </label>

                    <input
                        type="number"
                        name="min_price"
                        class="form-control"
                        min="0"
                        step="0.01"
                        value="{{ $minPrice }}"
                        placeholder="₹ Min"
                    >

                </div>


                <!-- Maximum Price -->

                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Maximum Price
                    </label>

                    <input
                        type="number"
                        name="max_price"
                        class="form-control"
                        min="0"
                        step="0.01"
                        value="{{ $maxPrice }}"
                        placeholder="₹ Max"
                    >

                </div>


                <!-- Sorting -->

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Sort Products
                    </label>

                    <select
                        name="sort"
                        class="form-select"
                    >

                        <option
                            value="relevance"
                            {{ $sort === 'relevance' ? 'selected' : '' }}
                        >
                            Relevance
                        </option>

                        <option
                            value="price_low"
                            {{ $sort === 'price_low' ? 'selected' : '' }}
                        >
                            Price: Low to High
                        </option>

                        <option
                            value="price_high"
                            {{ $sort === 'price_high' ? 'selected' : '' }}
                        >
                            Price: High to Low
                        </option>

                        <option
                            value="newest"
                            {{ $sort === 'newest' ? 'selected' : '' }}
                        >
                            Newest Products
                        </option>

                    </select>

                </div>


                <!-- Buttons -->

                <div class="col-md-8 d-flex align-items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        🔍 Search & Filter
                    </button>

                    <a
                        href="{{ route('products.search') }}"
                        class="btn btn-outline-secondary"
                    >
                        Clear Filters
                    </a>

                </div>

            </div>

        </form>

    </div>


    <!-- Recent & Popular Searches -->

    <div class="row mb-4">

        <!-- Recent Searches -->

        <div class="col-md-6 mb-3 mb-md-0">

            <div class="card shadow-sm search-history-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h5 class="mb-0">
                            🕘 Recent Searches
                        </h5>

                        @if ($recentSearches->count() > 0)

                            <form
                                action="{{ route('products.history.clear') }}"
                                method="POST"
                                onsubmit="return confirm('Clear your search history?')"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                >
                                    Clear
                                </button>

                            </form>

                        @endif

                    </div>


                    @forelse($recentSearches as $recent)

                        <a
                            href="{{ route('products.search', ['query' => $recent->keyword]) }}"
                            class="btn btn-outline-primary btn-sm search-chip"
                        >

                            🔎 {{ $recent->keyword }}

                        </a>

                    @empty

                        <p class="text-muted mb-0">
                            No recent searches yet.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>


        <!-- Popular Searches -->

        <div class="col-md-6">

            <div class="card shadow-sm search-history-card">

                <div class="card-body">

                    <h5 class="mb-3">
                        🔥 Popular Searches
                    </h5>


                    @forelse($popularSearches as $index => $popular)

                        <div class="popular-item">

                            <div>

                                <span class="rank-number">
                                    {{ $index + 1 }}
                                </span>

                                <a
                                    href="{{ route('products.search', ['query' => $popular->keyword]) }}"
                                    class="text-decoration-none fw-semibold"
                                >
                                    {{ $popular->keyword }}
                                </a>

                            </div>

                            <span class="badge bg-primary">
                                {{ $popular->total_searches }}
                                {{ $popular->total_searches == 1 ? 'search' : 'searches' }}
                            </span>

                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            No popular searches yet.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    <!-- Search Information -->

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            @if ($query)

                <h5 class="mb-1">

                    Search results for:

                    <span class="text-primary">
                        "{{ $query }}"
                    </span>

                </h5>

            @else

                <h5 class="mb-1">
                    All Products
                </h5>

            @endif

            <small class="text-muted">

                {{ $products->total() }}

                {{ $products->total() == 1 ? 'product' : 'products' }}

                found

            </small>

        </div>

    </div>


    <!-- Products -->

    <div class="card shadow p-3">

        @forelse($products as $p)

            <div class="border rounded p-3 mb-3 bg-white">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <h5 class="mb-1">
                            {{ $p->name }}
                        </h5>

                        <p class="text-muted mb-2">
                            {{ $p->description ?? 'No description available' }}
                        </p>

                        <h6 class="text-success">
                            ₹{{ number_format($p->price, 2) }}
                        </h6>

                    </div>


                    <div class="col-md-4 text-md-end">

                        <a
                            href="{{ route('products.show', $p->id) }}"
                            class="btn btn-info text-white btn-sm"
                        >
                            View
                        </a>

                        <a
                            href="{{ route('products.edit', $p->id) }}"
                            class="btn btn-warning btn-sm"
                        >
                            Edit
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="text-center py-5">

                <h5 class="text-danger">
                    No Products Found
                </h5>

                <p class="text-muted">
                    Try changing your search keyword or price filters.
                </p>

                <a
                    href="{{ route('products.list') }}"
                    class="btn btn-primary"
                >
                    View All Products
                </a>

            </div>

        @endforelse


        <!-- Pagination -->

        @if ($products->hasPages())

            <div class="mt-3">

                {{ $products->links() }}

            </div>

        @endif

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>