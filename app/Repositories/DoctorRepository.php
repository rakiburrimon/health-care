<?php

namespace App\Repositories;

use App\Models\Doctor;

class DoctorRepository {
    public function getAllDoctors() {
        return Doctor::with('user')->get();
    }

    public function getDoctorAvailability($doctorId) {
        return Doctor::findOrFail($doctorId)->availabilities;
    }
}
