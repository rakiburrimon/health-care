<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function bookAppointment(Request $request)
    {
        // Validate the request
        $request->validate([
            'doctor_id' => 'required|integer',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'appointment_date' => 'required|string',
        ]);

        // Get the authenticated user
        $user = $request->user();

        // Ensure the user is a patient
        if ($user->role !== 'patient') {
            return response()->json(['message' => 'Only patients can book appointments'], 403);
        }

        // Get the patient
        $patient = $user->patient;

        // Ensure the patient has not booked an appointment
        if ($patient->appointments()->first()) {
            return response()->json(['message' => 'Patient already has an appointment'], 400);
        }

        // If the 'appointment_date' is a Unix timestamp, convert it to datetime
        if (is_numeric($request->appointment_date)) {
            $appointmentDate = date('Y-m-d H:i:s', $request->appointment_date);
        } else {
            $appointmentDate = $request->appointment_date;  // If it's already in a valid format
        }

        // Create the appointment
        $appointment = $patient->appointments()->create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $patient->patient_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'appointment_date' => $appointmentDate,
            'status' => 'pending'
        ]);

        return response()->json(['message' => 'Appointment booked successfully', 'appointment' => $appointment], 200);
    }

    public function getPatientAppointments($patientId)
    {
        // Get the authenticated user
        $user = request()->user();

        // Ensure the user is a patient
        if ($user->role !== 'patient') {
            return response()->json(['message' => 'Only patients can view their appointments'], 403);
        }

        // Get the patient
        $patient = $user->patient;

        // Ensure the patient is the one making the request
        if ($patient->patient_id !== $patientId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Get the 'per_page' query parameter from the request, with a default value of 15
        $perPage = request()->get('per_page', 15);

        // Get the patient's appointments with pagination
        $appointments = $patient->appointments()->paginate($perPage);

        return response()->json([
            'appointments' => $appointments->items(),  // Get the items on the current page
            'pagination' => [
                'current_page' => $appointments->currentPage(),
                'total_pages' => $appointments->lastPage(),
                'total_items' => $appointments->total(),
                'per_page' => $appointments->perPage(),
            ]
        ], 200);
    }

    public function getDoctorAppointments($doctorId)
    {
        // Get the authenticated user
        $user = request()->user();

        // Ensure the user is a doctor
        if ($user->role !== 'doctor') {
            return response()->json(['message' => 'Only doctors can view their appointments'], 403);
        }

        // Get the doctor
        $doctor = $user->doctor;

        // Ensure the doctor is the one making the request
        if ($doctor->doctor_id !== $doctorId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Get the 'per_page' query parameter from the request, with a default value of 15
        $perPage = request()->get('per_page', 15);

        // Get the doctor's appointments with pagination
        $appointments = $doctor->appointments()->paginate($perPage);

        return response()->json([
            'appointments' => $appointments->items(),  // Get the items on the current page
            'pagination' => [
                'current_page' => $appointments->currentPage(),
                'total_pages' => $appointments->lastPage(),
                'total_items' => $appointments->total(),
                'per_page' => $appointments->perPage(),
            ]
        ], 200);
    }
}
