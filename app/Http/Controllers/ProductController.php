<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);


        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function show($id)
    {
        return Product::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function getProducts()
    {
            $products = Product::all();

if (!auth()->check()) {
    return response()->json(['message' => 'User not logged in'], 401);
}
            if(auth()->check()) {
                return response()->json([
                    'message' => 'Product list retrieved successfully',
                    'data' => $products,
                ]);
            }
            if ($products->isEmpty()) {
                return response()->json(['message' => 'No products found'], 404);
            }
            return response()->json([
                'message' => 'Product list retrieved successfully',
                'data' => $products,
            ]);
   }


}
