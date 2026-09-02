<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()->route('products.list')
            ->with('success', 'Product Added Successfully!');
    }

    // Show Product List
    public function list()
    {
        $products = Product::latest()->paginate(10);

        return view('products.list', compact('products'));
    }

    // Show Product Detail
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('products.show', compact('product'));
    }

    // Show Edit Product Form
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    // Update Product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()->route('products.list')
            ->with('success', 'Product Updated Successfully!');
    }

    // Delete Product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->route('products.list')
            ->with('success', 'Product Deleted Successfully!');
    }

    /**
     * Advanced Laravel Scout Search
     *
     * Features:
     * - Scout search
     * - Pagination
     * - Price range
     * - Sorting
     * - Search history
     * - Popular searches
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

        /*
        |--------------------------------------------------------------------------
        | Validate Price Range
        |--------------------------------------------------------------------------
        */

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
                    'min_price' => 'Minimum price cannot be greater than maximum price.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Save Search History
        |--------------------------------------------------------------------------
        */

        if ($query !== '') {
            $this->saveSearchHistory($request, $query);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Recent Searches
        |--------------------------------------------------------------------------
        */

        $recentSearches = SearchHistory::where(
            'session_id',
            $request->session()->getId()
        )
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Get Popular Searches
        |--------------------------------------------------------------------------
        */

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
        | Empty Query
        |--------------------------------------------------------------------------
        |
        | When there is no keyword, show all products.
        |
        */

        if ($query === '') {

            $products = Product::query();

            // Minimum price
            if ($minPrice !== null && $minPrice !== '') {
                $products->where('price', '>=', $minPrice);
            }

            // Maximum price
            if ($maxPrice !== null && $maxPrice !== '') {
                $products->where('price', '<=', $maxPrice);
            }

            /*
            |--------------------------------------------------------------------------
            | Sorting
            |--------------------------------------------------------------------------
            */

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
            | Laravel Scout Search
            |--------------------------------------------------------------------------
            */

            $products = Product::search($query)
                ->query(function ($builder) use (
                    $minPrice,
                    $maxPrice,
                    $sort
                ) {

                    // Minimum price
                    if ($minPrice !== null && $minPrice !== '') {
                        $builder->where('price', '>=', $minPrice);
                    }

                    // Maximum price
                    if ($maxPrice !== null && $maxPrice !== '') {
                        $builder->where('price', '<=', $maxPrice);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Sorting
                    |--------------------------------------------------------------------------
                    */

                    switch ($sort) {

                        case 'price_low':
                            $builder->orderBy('price', 'asc');
                            break;

                        case 'price_high':
                            $builder->orderBy('price', 'desc');
                            break;

                        case 'newest':
                            $builder->orderBy('created_at', 'desc');
                            break;
                    }
                })
                ->paginate(8)
                ->withQueryString();
        }

        return view('products.search', compact(
            'products',
            'query',
            'minPrice',
            'maxPrice',
            'sort',
            'recentSearches',
            'popularSearches'
        ));
    }

    /**
     * Save Search History
     */
    private function saveSearchHistory(Request $request, string $query): void
    {
        $sessionId = $request->session()->getId();

        /*
        |--------------------------------------------------------------------------
        | Check Last Search
        |--------------------------------------------------------------------------
        |
        | If the user searches the exact same keyword repeatedly,
        | don't create unnecessary rows.
        |
        */

        $lastSearch = SearchHistory::where('session_id', $sessionId)
            ->orderByDesc('updated_at')
            ->first();

        if ($lastSearch && $lastSearch->keyword === $query) {

            $lastSearch->increment('search_count');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Create New Search History
        |--------------------------------------------------------------------------
        */

        SearchHistory::create([
            'session_id' => $sessionId,
            'keyword' => $query,
            'search_count' => 1,
        ]);
    }

    /**
     * AJAX Live Search Suggestions
     */
    public function suggestions(Request $request)
    {
        $query = trim($request->query('query', ''));

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
                    'price' => number_format($product->price, 2),
                    'url' => route('products.show', $product->id),
                ];
            });

        return response()->json($products);
    }

    /**
     * Clear Current Session Search History
     */
    public function clearSearchHistory(Request $request)
    {
        SearchHistory::where(
            'session_id',
            $request->session()->getId()
        )->delete();

        return redirect()
            ->route('products.search')
            ->with('success', 'Search history cleared successfully!');
    }
}