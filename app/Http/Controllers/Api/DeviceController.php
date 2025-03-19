<?php

namespace App\Http\Controllers\Api;

use App\Models\Device;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        return response()->json(['devices' => $devices], 200);

    }

    // Show a specific device
    public function show($id)
    {
        $device = Device::find($id);
        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }
        return response()->json(['device' => $device], 200);

    }

    // Create a new device donation
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'number_of_device' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('devices', 'public');
            $data['image'] = $imagePath;
        }

        $device = Device::create($data);
        return response()->json(['device' => $device], 201);
    }

    // Update a device donation
    public function update(Request $request, $id)
    {
        $device = Device::find($id);
        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'number_of_device' => 'sometimes|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($device->image && Storage::disk('public')->exists($device->image)) {
                Storage::disk('public')->delete($device->image);
            }

            // Upload new image
            $imagePath = $request->file('image')->store('devices', 'public');
            $data['image'] = $imagePath;
        }

        $device->update($data);
        return response()->json(['device' => $device], 200);
    }

    // Delete a device donation
    public function destroy($id)
    {
        $device = Device::find($id);
        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $device->delete();
        return response()->json(['message' => 'Device deleted']);
    }
}
