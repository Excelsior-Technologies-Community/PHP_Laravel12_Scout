<!DOCTYPE html>
<html>

<head>

    <title>Product Detail</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <a
            href="{{ route('products.list') }}"
            class="btn btn-secondary mb-3">
            ⬅ Back
        </a>

        <div class="card shadow-lg p-4">

            <div class="d-flex justify-content-between">

                <h2>
                    {{ $product->name }}
                </h2>

                @if($product->is_featured)

                <span class="badge bg-warning text-dark h-25">
                    ⭐ Featured
                </span>

                @endif

            </div>


            <hr>


            <div class="mb-3">

                <strong>
                    Price:
                </strong>

                ₹{{ number_format($product->price, 2) }}

            </div>


            <div class="mb-3">

                <strong>
                    Status:
                </strong>

                @if($product->status === 'active')

                <span class="badge bg-success">
                    Active
                </span>

                @else

                <span class="badge bg-secondary">
                    Inactive
                </span>

                @endif

            </div>


            <div class="mb-4">

                <strong>
                    Description:
                </strong>

                <p>
                    {{ $product->description ?? 'No description available' }}
                </p>

            </div>


            <div class="mb-3">

                <strong>
                    Created At:
                </strong>

                {{ $product->created_at->format(
                'M d, Y H:i A'
            ) }}

            </div>


            <div class="d-flex gap-2">

                <a
                    href="{{ route('products.edit', $product->id) }}"
                    class="btn btn-warning">
                    Edit
                </a>

                <form
                    action="{{ route(
                    'products.featured',
                    $product->id
                ) }}"
                    method="POST">

                    @csrf
                    @method('PATCH')

                    <button
                        class="btn btn-outline-warning">
                        ⭐
                        {{ $product->is_featured
                        ? 'Remove Featured'
                        : 'Make Featured'
                    }}
                    </button>

                </form>

                <form
                    action="{{ route(
                    'products.delete',
                    $product->id
                ) }}"
                    method="POST"
                    onsubmit="return confirm('Move product to trash?')">

                    @csrf
                    @method('DELETE')

                    <button
                        class="btn btn-danger">
                        Delete
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>