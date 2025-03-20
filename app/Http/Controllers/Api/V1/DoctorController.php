<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function registerAsDoctor(Request $request)
    {
        // Validate the request
        $request->validate([
            'specialization' => 'required|string',
            'license_number' => 'required|string',
        ]);

        // Get the authenticated user
        $user = $request->user();

        $doctor = $user->doctor;

        if (!$doctor) {
            Doctor::create(['user_id' => $user->id, 'specialization' => $request->specialization, 'license_number' => $request->license_number]);
        }

        // Ensure the user is not already a doctor
        if ($user->role === 'doctor') {
            return response()->json(['message' => 'User is already a doctor'], 400);
        }

        // Update the user role to doctor
        $user->update(['role' => 'doctor']);

        return response()->json(['message' => 'User registered as a doctor successfully'], 200);
    }

    public function setAvailability(Request $request)
    {
        // Validate the request
        $request->validate([
            'day' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Get the authenticated user (doctor)
        $user = $request->user();

        // Ensure the user is a doctor
        if ($user->role !== 'doctor') {
            return response()->json(['message' => 'Only doctors can set availability'], 403);
        }

        // Get the doctor associated with the user
        $doctor = $user->doctor;

        if (!$doctor) {
            return response()->json(['message' => 'User is not registered as a doctor'], 400);
        }

        $availability = $doctor->availabilities()->where('day', $request->day)->where('start_time', $request->start_time)->where('end_time', $request->end_time)->first();

        if ($availability) {
            return response()->json(['message' => 'Availability already set for this day'], 400);
        }

        // Create the availability
        $availability = $doctor->availabilities()->create([
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json(['message' => 'Availability set successfully', 'data' => $availability], 201);
    }

    public function getAvailabilities(Request $request)
    {
        // Get the authenticated user (doctor)
        $user = $request->user();

        // Ensure the user is a doctor
        if ($user->role !== 'doctor') {
            return response()->json(['message' => 'Only doctors can get availabilities'], 403);
        }

        // Get the doctor associated with the user
        $doctor = $user->doctor;

        if (!$doctor) {
            return response()->json(['message' => 'User is not registered as a doctor'], 400);
        }

        // Get the 'per_page' query parameter from the request, with a default value of 15
        $perPage = $request->get('per_page', 15);

        // Get the doctor's availabilities with pagination
        $availabilities = $doctor->availabilities()->paginate($perPage);

        return response()->json([
            'data' => $availabilities->items(),  // Get the items on the current page
            'pagination' => [
                'current_page' => $availabilities->currentPage(),
                'total_pages' => $availabilities->lastPage(),
                'total_items' => $availabilities->total(),
                'per_page' => $availabilities->perPage(),
            ]
        ], 200);
    }
}
