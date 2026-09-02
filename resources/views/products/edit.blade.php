<!DOCTYPE html>
<html>

<head>

    <title>Edit Product</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="card shadow-lg p-4">

            <h2 class="text-center mb-4">
                Edit Product
            </h2>

            @if ($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

            @endif

            <form
                action="{{ route('products.update', $product->id) }}"
                method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $product->name) }}">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="3">{{ old('description', $product->description) }}</textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Price (₹)
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        class="form-control"
                        value="{{ old('price', $product->price) }}">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option
                            value="active"
                            {{ $product->status === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option
                            value="inactive"
                            {{ $product->status === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>

                <div class="form-check mb-4">

                    <input
                        type="checkbox"
                        name="is_featured"
                        value="1"
                        class="form-check-input"
                        id="featured"
                        {{ $product->is_featured ? 'checked' : '' }}>

                    <label
                        class="form-check-label"
                        for="featured">
                        ⭐ Featured Product
                    </label>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100">
                    Update Product
                </button>

                <a
                    href="{{ route('products.list') }}"
                    class="btn btn-secondary w-100 mt-3">
                    Back
                </a>

            </form>

        </div>

    </div>

</body>

</html>