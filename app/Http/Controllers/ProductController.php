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
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
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
