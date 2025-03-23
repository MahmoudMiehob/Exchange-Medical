<?php

namespace App\Http\Controllers\Api;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::all();
        return response()->json(['medicines' => $medicines], 200);
    }

    // Show a specific medicine
    public function show($id)
    {
        $medicine = Medicine::find($id);
        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }
        return response()->json(['medicine' => $medicine], 200);
    }

    // Create a new medicine donation
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'expire_date' => 'required|date',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('medicines', 'public');
            $data['image'] = $imagePath;
        }

        $medicine = Medicine::create($data);
return response()->json(['medicine' => $medicine], 201);

    }

    // Update a medicine donation
    public function update(Request $request, $id)
    {
        $medicine = Medicine::find($id);
        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'expire_date' => 'sometimes|date',
            'quantity' => 'sometimes|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($medicine->image && Storage::disk('public')->exists($medicine->image)) {
                Storage::disk('public')->delete($medicine->image);
            }

            // Upload new image
            $imagePath = $request->file('image')->store('medicines', 'public');
            $data['image'] = $imagePath;
        }

        $medicine->update($data);
        return response()->json(['medicine' => $medicine], 200);

    }
    // Delete a medicine donation
    public function destroy($id)
    {
        $medicine = Medicine::find($id);
        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }

        $medicine->delete();
        return response()->json(['message' => 'Medicine deleted']);
    }
}
