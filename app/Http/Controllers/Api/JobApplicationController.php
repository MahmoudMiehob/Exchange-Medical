<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class JobApplicationController extends Controller
{
    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_offer_id' => 'required|exists:jobs_offer,id',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'skill' => 'required|string',
            'type_of_disability' => 'nullable|string|max:255',
            'personal_image' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $imagePath = null;
            if ($request->hasFile('personal_image')) {
                $imagePath = $request->file('personal_image')->store('applications', 'public');
            }

            $application = JobApplication::create([
                'job_offer_id' => $request->job_offer_id,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'skill' => $request->skill,
                'type_of_disability' => $request->type_of_disability,
                'personal_image' => $imagePath
            ]);

            return response()->json([
                'message' => 'Application submitted successfully',
                'application' => $application
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to submit application',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        try {
            $applications = JobApplication::with('jobOffer')->latest()->get();
            return response()->json(['applications' => $applications], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function showApplicationsByJob($job_offer_id)
    {
        if (!Job::find($job_offer_id)) {
            return response()->json([
                'status' => false,
                'message' => 'Job offer not found'
            ], 404);
        }

        $applications = JobApplication::where('job_offer_id', $job_offer_id)
            ->with('jobOffer')
            ->latest()
            ->get();


        return response()->json(['applications' => $applications], 200);

    }
}
