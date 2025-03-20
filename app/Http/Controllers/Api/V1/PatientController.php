<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function registerAsPatient(Request $request)
    {
        // Validate the request
        $request->validate([
            'phone_number' => 'required|string',
            'address' => 'required|string',
        ]);
        
        // Get the authenticated user
        $user = $request->user();

        $patient = $user->patient;

        if (!$patient) {
            Patient::create(['user_id' => $user->id, 'phone_number' => $request->phone_number, 'address' => $request->address]);
        }

        // Ensure the user is not already a patient
        if ($user->role === 'patient') {
            return response()->json(['message' => 'User is already a patient'], 400);
        }

        // Update the user role to patient
        $user->update(['role' => 'patient']);

        return response()->json(['message' => 'User registered as a patient successfully'], 200);
    }
}
