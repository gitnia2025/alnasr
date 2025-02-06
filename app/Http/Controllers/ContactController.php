<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    // Display a listing of the contacts
    public function index()
    {
        $contacts = Contact::all();
        return response()->json($contacts);
    }

    // Store a newly created contact
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        // Create the contact
        $contact = Contact::create($request->all());

        return response()->json($contact, Response::HTTP_CREATED);
    }

    // Display a specific contact
    public function show(Contact $contact)
    {
        return response()->json($contact);
    }

    // Update the specified contact
    public function update(Request $request, Contact $contact)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        // Update the contact with new data
        $contact->update($request->all());

        return response()->json($contact);
    }

    // Delete the specified contact
    public function destroy(Contact $contact)
    {
        // Delete the contact
        $contact->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}