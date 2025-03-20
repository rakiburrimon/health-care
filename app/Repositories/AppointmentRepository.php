<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository {
    public function createAppointment(array $data) {
        return Appointment::create($data);
    }

    public function getPatientAppointments($patientId) {
        return Appointment::where('patient_id', $patientId)->get();
    }

    public function getDoctorAppointments($doctorId) {
        return Appointment::where('doctor_id', $doctorId)->get();
    }
}
