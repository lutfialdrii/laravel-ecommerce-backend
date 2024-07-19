<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'string',
        ]);

        $category = Category::create([
            'seller_id' => $request->user()->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created',
            'data' => $category,
        ], 201);
    }

    public function index(Request $request)
    {

        $categories = Category::where('seller_id', $request->user()->id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Categories',
            'data' => $categories
        ], 200);
    }
}
