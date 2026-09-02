<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Add Product
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('products.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store Product
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $request->input('status', 'active'),
        ]);

        return redirect()
            ->route('products.list')
            ->with('success', 'Product Added Successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | Product List + Advanced Filters
    |--------------------------------------------------------------------------
    */

    public function list(Request $request)
    {
        $query = Product::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status') &&
            in_array($request->status, ['active', 'inactive'])
        ) {

            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Featured Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('featured')) {

            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            }

            if ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        switch ($request->sort) {

            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;

            case 'price_low':
                $query->orderBy('price', 'asc');
                break;

            case 'price_high':
                $query->orderBy('price', 'desc');
                break;

            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            default:
                $query->latest();
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = [
            'total' => Product::count(),

            'active' => Product::where('status', 'active')->count(),

            'inactive' => Product::where('status', 'inactive')->count(),

            'featured' => Product::where('is_featured', true)->count(),

            'average_price' => Product::avg('price') ?? 0,

            'highest_price' => Product::max('price') ?? 0,

            'lowest_price' => Product::min('price') ?? 0,

            'trashed' => Product::onlyTrashed()->count(),
        ];

        return view(
            'products.list',
            compact('products', 'statistics')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Product Detail
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('products.show', compact('product'));
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Product
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Product
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $request->status,
        ]);

        return redirect()
            ->route('products.list')
            ->with('success', 'Product Updated Successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Product
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()
            ->route('products.list')
            ->with('success', 'Product moved to trash successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Featured
    |--------------------------------------------------------------------------
    */

    public function toggleFeatured($id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'is_featured' => !$product->is_featured,
        ]);

        return back()->with(
            'success',
            $product->is_featured
                ? 'Product marked as featured!'
                : 'Product removed from featured!'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'status' => $product->status === 'active'
                ? 'inactive'
                : 'active',
        ]);

        return back()->with(
            'success',
            'Product status updated successfully!'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk Delete
    |--------------------------------------------------------------------------
    */

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        Product::whereIn('id', $request->product_ids)->delete();

        return back()->with(
            'success',
            count($request->product_ids) . ' product(s) moved to trash!'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk Status
    |--------------------------------------------------------------------------
    */

    public function bulkStatus(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id',
            'status' => 'required|in:active,inactive',
        ]);

        Product::whereIn('id', $request->product_ids)
            ->update([
                'status' => $request->status,
            ]);

        return back()->with(
            'success',
            count($request->product_ids) .
                ' product(s) status updated successfully!'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Duplicate Product
    |--------------------------------------------------------------------------
    */

    public function duplicate($id)
    {
        $product = Product::findOrFail($id);

        $duplicate = $product->replicate();

        $duplicate->name = $product->name . ' - Copy';
        $duplicate->is_featured = false;
        $duplicate->status = 'active';

        $duplicate->save();

        return back()->with(
            'success',
            'Product duplicated successfully!'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Trash
    |--------------------------------------------------------------------------
    */

    public function trash()
    {
        $products = Product::onlyTrashed()
            ->latest('deleted_at')
            ->paginate(5);

        return view('products.trash', compact('products'));
    }

    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $product->restore();

        return back()->with(
            'success',
            'Product restored successfully!'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Permanently Delete
    |--------------------------------------------------------------------------
    */

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $product->forceDelete();

        return back()->with(
            'success',
            'Product permanently deleted!'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CSV Export
    |--------------------------------------------------------------------------
    */

    public function export(Request $request): StreamedResponse
    {
        $query = Product::query();

        /*
        | Search
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere(
                        'description',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        /*
        | Status
        */

        if (
            $request->filled('status') &&
            in_array($request->status, ['active', 'inactive'])
        ) {

            $query->where('status', $request->status);
        }

        /*
        | Featured
        */

        if ($request->filled('featured')) {

            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            }

            if ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        /*
        | Price
        */

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query
            ->latest()
            ->get();

        $filename = 'products-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(function () use ($products) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'Name',
                'Description',
                'Price',
                'Status',
                'Featured',
                'Created At',
            ]);

            foreach ($products as $product) {

                fputcsv($handle, [
                    $product->id,
                    $product->name,
                    $product->description,
                    $product->price,
                    $product->status,
                    $product->is_featured ? 'Yes' : 'No',
                    $product->created_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Advanced Scout Search
    |--------------------------------------------------------------------------
    */

    public function search(Request $request)
    {
        $query = trim($request->query('query', ''));

        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');

        $sort = $request->query('sort', 'relevance');

        $request->validate([
            'query' => 'nullable|string|max:255',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'sort' => 'nullable|in:relevance,price_low,price_high,newest',
        ]);

        if (
            $minPrice !== null &&
            $maxPrice !== null &&
            $minPrice !== '' &&
            $maxPrice !== '' &&
            $minPrice > $maxPrice
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'min_price' =>
                    'Minimum price cannot be greater than maximum price.',
                ]);
        }

        if ($query !== '') {
            $this->saveSearchHistory($request, $query);
        }

        $recentSearches = SearchHistory::where(
            'session_id',
            $request->session()->getId()
        )
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $popularSearches = SearchHistory::select(
            'keyword',
            DB::raw('SUM(search_count) as total_searches')
        )
            ->groupBy('keyword')
            ->orderByDesc('total_searches')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Empty Search
        |--------------------------------------------------------------------------
        */

        if ($query === '') {

            $products = Product::query();

            if ($minPrice !== null && $minPrice !== '') {
                $products->where('price', '>=', $minPrice);
            }

            if ($maxPrice !== null && $maxPrice !== '') {
                $products->where('price', '<=', $maxPrice);
            }

            switch ($sort) {

                case 'price_low':
                    $products->orderBy('price', 'asc');
                    break;

                case 'price_high':
                    $products->orderBy('price', 'desc');
                    break;

                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;

                default:
                    $products->latest();
                    break;
            }

            $products = $products
                ->paginate(8)
                ->withQueryString();
        } else {

            /*
            |--------------------------------------------------------------------------
            | Scout Search
            |--------------------------------------------------------------------------
            */

            $products = Product::search($query)
                ->query(function ($builder) use (
                    $minPrice,
                    $maxPrice,
                    $sort
                ) {

                    if (
                        $minPrice !== null &&
                        $minPrice !== ''
                    ) {
                        $builder->where(
                            'price',
                            '>=',
                            $minPrice
                        );
                    }

                    if (
                        $maxPrice !== null &&
                        $maxPrice !== ''
                    ) {
                        $builder->where(
                            'price',
                            '<=',
                            $maxPrice
                        );
                    }

                    switch ($sort) {

                        case 'price_low':
                            $builder->orderBy(
                                'price',
                                'asc'
                            );
                            break;

                        case 'price_high':
                            $builder->orderBy(
                                'price',
                                'desc'
                            );
                            break;

                        case 'newest':
                            $builder->orderBy(
                                'created_at',
                                'desc'
                            );
                            break;
                    }
                })
                ->paginate(8)
                ->withQueryString();
        }

        return view(
            'products.search',
            compact(
                'products',
                'query',
                'minPrice',
                'maxPrice',
                'sort',
                'recentSearches',
                'popularSearches'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Save Search History
    |--------------------------------------------------------------------------
    */

    private function saveSearchHistory(
        Request $request,
        string $query
    ): void {

        $sessionId = $request->session()->getId();

        $lastSearch = SearchHistory::where(
            'session_id',
            $sessionId
        )
            ->orderByDesc('updated_at')
            ->first();

        if (
            $lastSearch &&
            $lastSearch->keyword === $query
        ) {

            $lastSearch->increment('search_count');

            return;
        }

        SearchHistory::create([
            'session_id' => $sessionId,
            'keyword' => $query,
            'search_count' => 1,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX Suggestions
    |--------------------------------------------------------------------------
    */

    public function suggestions(Request $request)
    {
        $query = trim(
            $request->query('query', '')
        );

        if ($query === '') {
            return response()->json([]);
        }

        $products = Product::search($query)
            ->take(5)
            ->get()
            ->map(function ($product) {

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => number_format(
                        $product->price,
                        2
                    ),
                    'url' => route(
                        'products.show',
                        $product->id
                    ),
                ];
            });

        return response()->json($products);
    }

    /*
    |--------------------------------------------------------------------------
    | Clear Search History
    |--------------------------------------------------------------------------
    */

    public function clearSearchHistory(Request $request)
    {
        SearchHistory::where(
            'session_id',
            $request->session()->getId()
        )->delete();

        return redirect()
            ->route('products.search')
            ->with(
                'success',
                'Search history cleared successfully!'
            );
    }
}
