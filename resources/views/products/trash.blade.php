<!DOCTYPE html>
<html>

<head>

    <title>Product Trash</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2>
                🗑️ Product Trash
            </h2>

            <a
                href="{{ route('products.list') }}"
                class="btn btn-secondary">
                ← Back to Products
            </a>

        </div>


        @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

        @endif


        <div class="card shadow">

            <div class="table-responsive">

                <table class="table table-striped mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th>Name</th>

                            <th>Price</th>

                            <th>Status</th>

                            <th>Deleted At</th>

                            <th>Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($products as $product)

                        <tr>

                            <td>
                                {{ $product->name }}
                            </td>

                            <td>
                                ₹{{ number_format($product->price, 2) }}
                            </td>

                            <td>

                                @if($product->status === 'active')

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

                                {{ $product->deleted_at?->format(
                                'd M Y H:i'
                            ) }}

                            </td>

                            <td>

                                <form
                                    action="{{ route(
                                    'products.restore',
                                    $product->id
                                ) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        class="btn btn-sm btn-success">
                                        ♻️ Restore
                                    </button>

                                </form>


                                <form
                                    action="{{ route(
                                    'products.force.delete',
                                    $product->id
                                ) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('This will permanently delete the product. Continue?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-sm btn-danger">
                                        Delete Forever
                                    </button>

                                </form>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-5">

                                <h5>
                                    Trash is empty
                                </h5>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="p-3">

                {{ $products->links() }}

            </div>

        </div>

    </div>

</body>

</html>