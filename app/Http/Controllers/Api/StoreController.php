<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $stores = User::where('roles', 'seller')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'List Store',
            'data' => $stores
        ]);
    }

    // get Product by Store
    public function productByStore(Request $request, $id)
    {
        $products = Product::where('seller_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'List Product by Store',
            'data' => $products
        ]);
    }

    public function livestreaming(Request $request)
    {
        $stores = User::where('roles', 'seller')->where('is_livestreaming', true)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'List Stores livestreaming',
            'data' => $stores
        ]);
    }
}
