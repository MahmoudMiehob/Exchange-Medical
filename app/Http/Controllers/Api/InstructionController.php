<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Instruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InstructionController extends Controller
{
    public function index()
    {
        $instructions = Instruction::all();
        return response()->json(['instructions' => $instructions], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('instructions', 'public');
            $data['image'] = $imagePath;
        } else {
            $data['image'] = null;
        }

        $instruction = Instruction::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $data['image'],
        ]);

        return response()->json([
            'message' => 'Instruction created successfully',
            'instruction' => $instruction,
        ], 201);
    }

    public function show($id)
    {
        $instruction = Instruction::find($id);

        if (!$instruction) {
            return response()->json(['message' => 'Instruction not found'], 404);
        }

        return response()->json(['instruction' => $instruction], 200);
    }
    public function update(Request $request, $id)
    {
        $instruction = Instruction::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            if ($instruction->image && Storage::disk('public')->exists($instruction->image)) {
                Storage::disk('public')->delete($instruction->image);
            }
            $imagePath = $request->file('image')->store('instructions', 'public');
            $data['image'] = $imagePath;
        }

        // Update only if there are changes
        if (!empty($data)) {
            $instruction->update($data);
        }

        // Refresh the model to get the latest data
        $instruction->refresh();

        return response()->json([
            'message' => 'Instruction updated successfully',
            'instruction' => $instruction,
        ], 200);
    }

    public function destroy($id)
    {
        $instruction = Instruction::findOrFail($id);

        if ($instruction->image && Storage::disk('public')->exists($instruction->image)) {
            Storage::disk('public')->delete($instruction->image);
        }

        $instruction->delete();

        return response()->json(['message' => 'Instruction deleted successfully'], 200);
    }
}
