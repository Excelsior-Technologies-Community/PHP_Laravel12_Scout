<!DOCTYPE html>
<html>

<head>
    <title>Add Product</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="card shadow-lg p-4">

            <h2 class="text-center mb-4">
                Add New Product
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
                action="{{ route('products.store') }}"
                method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        placeholder="Enter product name">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="3"
                        placeholder="Write details">{{ old('description') }}</textarea>

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
                        value="{{ old('price') }}"
                        placeholder="0.00">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="active">
                            Active
                        </option>

                        <option value="inactive">
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
                        id="featured">

                    <label
                        class="form-check-label"
                        for="featured">
                        ⭐ Mark as Featured Product
                    </label>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100">
                    Add Product
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