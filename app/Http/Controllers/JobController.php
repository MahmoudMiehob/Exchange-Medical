<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::all();
        return response()->json(['jobs' => $jobs], 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'place' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('jobs', 'public'); // Save image to storage
            $data['image'] = $imagePath;
        } else {
            $data['image'] = null;
        }

        $job = Job::create([
            'name' => $data['name'],
            'place' => $data['place'],
            'image' => $data['image'],
        ]);
        return response()->json([
            'message' => 'Job created successfully',
            'job' => $job,
        ], 201);
    }




    // Get a single job by ID
    public function show($id)
    {
        $job = Job::find($id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        return response()->json(['job' => $job], 200);
    }





    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'place' => 'sometimes|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            if ($job->image && Storage::disk('public')->exists($job->image)) {
                Storage::disk('public')->delete($job->image);
            }

            $imagePath = $request->file('image')->store('jobs', 'public');
            $data['image'] = $imagePath; // Add the new image path to the data array
        }

        $job->update($data);

        return response()->json([
            'message' => 'Job updated successfully',
            'job' => $job,
        ], 200);
    }




    // Delete a job
    public function destroy($id)
    {
        $job = Job::findOrFail($id);

        if ($job->image && Storage::disk('public')->exists($job->image)) {
            Storage::disk('public')->delete($job->image);
        }

        $job->delete();

        return response()->json(['message' => 'Job deleted successfully'], 200);
    }
}
