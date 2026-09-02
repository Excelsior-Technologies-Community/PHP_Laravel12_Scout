<!DOCTYPE html>
<html>

<head>

    <title>Product Management</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container-fluid mt-4 px-4">

        {{-- Success Message --}}

        @if (session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

        @endif


        {{-- Header --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">
                    📦 Product Management
                </h2>

                <small class="text-muted">
                    Manage products, status, featured products and trash
                </small>

            </div>

            <div class="d-flex gap-2">

                <a
                    href="{{ route('products.trash') }}"
                    class="btn btn-outline-danger">
                    🗑️ Trash
                </a>

                <a
                    href="{{ route('products.create') }}"
                    class="btn btn-success">
                    + Add Product
                </a>

            </div>

        </div>


        {{-- Statistics --}}

        <div class="row g-3 mb-4">

            <div class="col-md-3">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Total Products
                        </small>

                        <h3>
                            {{ $statistics['total'] }}
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Active Products
                        </small>

                        <h3 class="text-success">
                            {{ $statistics['active'] }}
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Inactive Products
                        </small>

                        <h3 class="text-secondary">
                            {{ $statistics['inactive'] }}
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Featured Products
                        </small>

                        <h3 class="text-warning">
                            ⭐ {{ $statistics['featured'] }}
                        </h3>

                    </div>

                </div>

            </div>

        </div>


        {{-- Price Statistics --}}

        <div class="row g-3 mb-4">

            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Average Price
                        </small>

                        <h4>
                            ₹{{ number_format($statistics['average_price'], 2) }}
                        </h4>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Highest Price
                        </small>

                        <h4>
                            ₹{{ number_format($statistics['highest_price'], 2) }}
                        </h4>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Lowest Price
                        </small>

                        <h4>
                            ₹{{ number_format($statistics['lowest_price'], 2) }}
                        </h4>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Trash
                        </small>

                        <h4 class="text-danger">
                            {{ $statistics['trashed'] }}
                        </h4>

                    </div>

                </div>

            </div>

        </div>


        {{-- Advanced Filters --}}

        <div class="card shadow-sm p-4 mb-4">

            <form
                method="GET"
                action="{{ route('products.list') }}">

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Product name...">

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">
                            Min Price
                        </label>

                        <input
                            type="number"
                            name="min_price"
                            class="form-control"
                            value="{{ request('min_price') }}"
                            min="0"
                            step="0.01">

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">
                            Max Price
                        </label>

                        <input
                            type="number"
                            name="max_price"
                            class="form-control"
                            value="{{ request('max_price') }}"
                            min="0"
                            step="0.01">

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                    <div class="col-md-1">

                        <label class="form-label">
                            Featured
                        </label>

                        <select
                            name="featured"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="yes"
                                {{ request('featured') === 'yes' ? 'selected' : '' }}>
                                Yes
                            </option>

                            <option
                                value="no"
                                {{ request('featured') === 'no' ? 'selected' : '' }}>
                                No
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">
                            Sort By
                        </label>

                        <select
                            name="sort"
                            class="form-select">

                            <option value="">
                                Newest
                            </option>

                            <option
                                value="oldest"
                                {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                                Oldest
                            </option>

                            <option
                                value="name_asc"
                                {{ request('sort') === 'name_asc' ? 'selected' : '' }}>
                                Name A-Z
                            </option>

                            <option
                                value="name_desc"
                                {{ request('sort') === 'name_desc' ? 'selected' : '' }}>
                                Name Z-A
                            </option>

                            <option
                                value="price_low"
                                {{ request('sort') === 'price_low' ? 'selected' : '' }}>
                                Price Low-High
                            </option>

                            <option
                                value="price_high"
                                {{ request('sort') === 'price_high' ? 'selected' : '' }}>
                                Price High-Low
                            </option>

                        </select>

                    </div>

                </div>

                <div class="mt-3 d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary">
                        🔎 Apply Filters
                    </button>

                    <a
                        href="{{ route('products.list') }}"
                        class="btn btn-outline-secondary">
                        Clear
                    </a>

                    <a
                        href="{{ route('products.export', request()->query()) }}"
                        class="btn btn-outline-success">
                        📥 Export CSV
                    </a>

                </div>

            </form>

        </div>


        {{-- Bulk Actions --}}

        <form
            method="POST"
            id="bulkForm">

            @csrf

            <div class="card shadow-sm">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <strong>
                                Products
                            </strong>

                            <span class="badge bg-primary">
                                {{ $products->total() }}
                            </span>

                        </div>

                        <div class="d-flex gap-2">

                            <button
                                type="button"
                                onclick="bulkStatus('active')"
                                class="btn btn-sm btn-success">
                                🟢 Active
                            </button>

                            <button
                                type="button"
                                onclick="bulkStatus('inactive')"
                                class="btn btn-sm btn-secondary">
                                ⚪ Inactive
                            </button>

                            <button
                                type="button"
                                onclick="bulkDelete()"
                                class="btn btn-sm btn-danger">
                                🗑️ Bulk Delete
                            </button>

                        </div>

                    </div>

                </div>


                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    <input
                                        type="checkbox"
                                        id="selectAll"
                                        class="form-check-input">
                                </th>

                                <th>Name</th>

                                <th>Description</th>

                                <th>Price</th>

                                <th>Status</th>

                                <th>Featured</th>

                                <th>Created</th>

                                <th class="text-center">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($products as $p)

                            <tr>

                                <td>

                                    <input
                                        type="checkbox"
                                        name="product_ids[]"
                                        value="{{ $p->id }}"
                                        class="form-check-input product-checkbox">

                                </td>

                                <td>

                                    <strong>
                                        {{ $p->name }}
                                    </strong>

                                </td>

                                <td>

                                    {{ \Illuminate\Support\Str::limit(
    $p->description ?? 'No description',
    50
) }}

                                </td>

                                <td>

                                    <strong>
                                        ₹{{ number_format($p->price, 2) }}
                                    </strong>

                                </td>

                                <td>

                                    @if($p->status === 'active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                    @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                    @endif

                                </td>

                                <td>

                                    @if($p->is_featured)

                                    <span class="badge bg-warning text-dark">
                                        ⭐ Featured
                                    </span>

                                    @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                    @endif

                                </td>

                                <td>

                                    {{ $p->created_at->format('d M Y') }}

                                </td>

                                <td class="text-center">

                                    <div class="d-flex justify-content-center gap-1 flex-wrap">

                                        <a
                                            href="{{ route('products.show', $p->id) }}"
                                            class="btn btn-sm btn-info text-white">
                                            View
                                        </a>

                                        <a
                                            href="{{ route('products.edit', $p->id) }}"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('products.featured', $p->id) }}"
                                            method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                class="btn btn-sm btn-outline-warning"
                                                title="Toggle Featured">
                                                ⭐
                                            </button>

                                        </form>

                                        <form
                                            action="{{ route('products.status', $p->id) }}"
                                            method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                class="btn btn-sm btn-outline-success"
                                                title="Toggle Status">
                                                {{ $p->status === 'active' ? '🔴' : '🟢' }}
                                            </button>

                                        </form>

                                        <form
                                            action="{{ route('products.duplicate', $p->id) }}"
                                            method="POST">

                                            @csrf

                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                title="Duplicate">
                                                📋
                                            </button>

                                        </form>

                                        <form
                                            action="{{ route('products.delete', $p->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Move this product to trash?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                class="btn btn-sm btn-danger">
                                                🗑️
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5">

                                    <h5>
                                        No Products Found
                                    </h5>

                                    <p class="text-muted">
                                        Try changing your filters.
                                    </p>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}

                <div class="p-3">

                    {{ $products->links() }}

                </div>

            </div>

        </form>

    </div>


    <script>
        document.getElementById('selectAll').addEventListener(
            'change',
            function() {

                document
                    .querySelectorAll('.product-checkbox')
                    .forEach(function(checkbox) {

                        checkbox.checked = this.checked;

                    }, this);

            }
        );


        function getSelectedProducts() {
            return Array.from(
                document.querySelectorAll(
                    '.product-checkbox:checked'
                )
            ).map(function(checkbox) {

                return checkbox.value;

            });
        }


        function bulkDelete() {
            const selected = getSelectedProducts();

            if (selected.length === 0) {

                alert('Please select at least one product.');

                return;
            }

            if (!confirm(
                    'Are you sure you want to move selected products to trash?'
                )) {
                return;
            }

            const form = document.getElementById('bulkForm');

            form.action = "{{ route('products.bulk.delete') }}";

            form.submit();
        }


        function bulkStatus(status) {
            const selected = getSelectedProducts();

            if (selected.length === 0) {

                alert('Please select at least one product.');

                return;
            }

            const form = document.getElementById('bulkForm');

            form.action =
                "{{ route('products.bulk.status') }}";

            const statusInput =
                document.createElement('input');

            statusInput.type = 'hidden';

            statusInput.name = 'status';

            statusInput.value = status;

            form.appendChild(statusInput);

            form.submit();
        }
    </script>

</body>

</html>