<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Specialist;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    // Create a new Specialist
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $specialist = Specialist::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ]);

        return response()->json($specialist, 201);
    }

    // Get all Specialists
    public function index()
    {
        $specialists = Specialist::all();
        return response()->json($specialists);
    }

    // Get a single Specialist by ID
    public function show($id)
    {
        $specialist = Specialist::findOrFail($id);
        return response()->json($specialist);
    }

    // Update a Specialist by ID
    public function update(Request $request, $id)
    {
        $specialist = Specialist::findOrFail($id);
        
        $specialist->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ]);

        return response()->json($specialist);
    }

    // Delete a Specialist by ID
    public function destroy($id)
    {
        $specialist = Specialist::findOrFail($id);
        $specialist->delete();
        return response()->json(['message' => 'Specialist deleted successfully']);
    }
}
