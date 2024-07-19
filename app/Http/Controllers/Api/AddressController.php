<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses;

        return response()->json([
            'status' => 'success',
            'message' => "addresses",
            'data' => $addresses
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'country' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'required|string',
        ]);

        $address = Address::create([
            'user_id' => $request->user()->id,
            'address' => $request->address,
            'country' => $request->country,
            'province' => $request->province,
            'city' => $request->city,
            'district' => $request->district,
            'postal_code' => $request->postal_code,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Address created',
            'data' => $address
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'address' => 'required|string',
            'country' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'required|string',
        ]);

        $address = Address::find($id);

        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'address not found',
            ], 404);
        }

        $address->update([
            'address' => $request->address,
            'country' => $request->country,
            'province' => $request->province,
            'city' => $request->city,
            'district' => $request->district,
            'postal_code' => $request->postal_code,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Address updated',
            'data' => $address
        ], 200);
    }

    public function destroy($id)
    {

        $address = Address::find($id);

        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'address not found',
            ], 404);
        }

        $address->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Address deleted',
        ], 200);
    }
}
