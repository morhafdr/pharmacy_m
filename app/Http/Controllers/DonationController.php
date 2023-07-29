<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Donation;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::all();
        return response()->json(['data' => $donations]);
    }

    public function show($id)
    {
        $donation = Donation::find($id);
        if (!$donation) {
            return response()->json(['message' => 'Donation not found'], 404);
        }
        return response()->json(['data' => $donation]);
    }

    public function store(Request $request)
    {
        $donation = new Donation();
        $donation->product_name = $request->input('product_name');
        $donation->paracode = $request->input('paracode');
        $donation->quantity = $request->input('quantity');
        $donation->expiry_date = $request->input('expiry_date');
        $donation->save();
        return response()->json(['message' => 'Donation created successfully', 'data' => $donation]);
    }

    public function update(Request $request, $id)
    {
        $donation = Donation::find($id);
        if (!$donation) {
            return response()->json(['message' => 'Donation not found'], 404);
        }
        $donation->product_name = $request->input('product_name');
        $donation->paracode = $request->input('paracode');
        $donation->quantity = $request->input('quantity');
        $donation->expiry_date = $request->input('expiry_date');
        $donation->save();
        return response()->json(['message' => 'Donation updated successfully', 'data' => $donation]);
    }

    public function destroy($id)
    {
        $donation = Donation::find($id);
        if (!$donation) {
            return response()->json(['message' => 'Donation not found'], 404);
        }
        $donation->delete();
        return response()->json(['message' => 'Donation deleted successfully']);
    }

    public function search(Request $request)
    {
        $product_name = $request->input('product_name');
        $paracode = $request->input('paracode');
        $expiry_date = $request->input('expiry_date');

        $donations = Donation::where('product_name', 'like', "%$product_name%")
                             ->where('paracode', 'like', "%$paracode%")
                             ->where('expiry_date', 'like', "%$expiry_date%")
                             ->get();

        if ($donations->isEmpty()) {
            return response()->json(['message' => 'Donations not found']);
        }
        return response()->json(['data' => $donations]);
    }
}