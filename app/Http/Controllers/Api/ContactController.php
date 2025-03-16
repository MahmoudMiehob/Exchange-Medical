<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return response()->json(['contacts' => $contacts], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'documentation' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'subject' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('documentation')) {
            $imagePath = $request->file('documentation')->store('contacts', 'public');
            $data['documentation'] = $imagePath;
        } else {
            $data['documentation'] = null;
        }

        $contact = Contact::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'phone_number' => $data['phone_number'],
            'documentation' => $data['documentation'],
            'subject' => $data['subject'],
        ]);

        return response()->json([
            'message' => 'Contact created successfully',
            'contact' => $contact,
        ], 201);
    }
}
