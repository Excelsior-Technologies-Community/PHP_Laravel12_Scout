# PHP_Laravel12_Scout

# Step 1 : Install  Laravel 12 
```php
 composer create-project laravel/laravel PHP_Laravel12_Scout
```
# Step 2: Database Setup for .env file
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=root
DB_PASSWORD=
```
# Step 3: Install Laravel Scout
```
composer require laravel/scout
```
# Step 4: Publish Scout Config
```php
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```
# config/scout.php open and update this method
```php
'driver' => 'database',
```
# Step 5: Create Products Table
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
             $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```
# Step 6: Create Migration File For Table Create
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];
}
```
# Step 7: Create Products Controller
```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show Add Product Form
    public function create()
    {
        return view('products.create');
    }

    // Store Product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
        ]);

        return redirect()->route('products.list')
            ->with('success', 'Product Added Successfully!');
    }

    // Show Product List
    public function list()
    {
        $products = Product::all();
        return view('products.list', compact('products'));
    }

    // Search
   public function search(Request $request)
{
    $query = $request->query('query');

    $products = Product::where('name', 'LIKE', "%$query%")
                ->orWhere('description', 'LIKE', "%$query%")
                ->get();

    return view('products.search', compact('products', 'query'));
}

}
```
# Step 8: Create Web.php route
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'list'])->name('products.list');

Route::get('/product/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/product/store', [ProductController::class, 'store'])->name('products.store');

Route::get('/search', [ProductController::class, 'search'])->name('products.search');
```
# Step 9: Create Blade file for resource/view/products folder
# resource/view/products/create.blade.php
```php
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Add New Product</h2>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter product name">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Write details"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Price (₹)</label>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00">
            </div>

            <button type="submit" class="btn btn-primary w-100">Add Product</button>

            <a href="{{ route('products.list') }}" class="btn btn-secondary w-100 mt-3">Back</a>
        </form>
    </div>
</div>

</body>
</html>
```
# resource/view/products/list.blade.php
```php
<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    
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
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->description }}</td>
                    <td><b>₹{{ $p->price }}</b></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
```

# resource/view/products/search.blade.php
```php
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
```
# Now Run Server and Paste this Url
```php
php artisan serve
```
```php
http://127.0.0.1:8000/product/create
```
 
 
 <img width="1604" height="643" alt="image" src="https://github.com/user-attachments/assets/7ca70b07-631d-4137-9792-9759d27e7d71" />

<img width="1655" height="422" alt="image" src="https://github.com/user-attachments/assets/92384ca7-8836-42e8-8ae3-d0fa31bb9b3e" />

<img width="1618" height="403" alt="image" src="https://github.com/user-attachments/assets/423d3628-2d0f-4871-8b14-8c8b9c133f02" />




