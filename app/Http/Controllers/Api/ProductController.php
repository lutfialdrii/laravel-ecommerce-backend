<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('seller_id', $request->user()->id)->with('seller')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Products',
            'data' => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'description' => 'string',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'required|image'
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('assets/product', 'public');
        }

        $product = Product::create([
            'seller_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $image,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created',
            'data' => $product
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'description' => 'string',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'required|image'
        ]);

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('assets/product', 'public');
            $product->image = $image;
            $product->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated',
            'data' => $product
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted'
        ], 200);
    }
}
