<?php

namespace App\Services;

use App\Repositories\DoctorRepository;

class DoctorService {
    protected $doctorRepository;

    public function __construct(DoctorRepository $doctorRepository) {
        $this->doctorRepository = $doctorRepository;
    }

    public function getAllDoctors() {
        return $this->doctorRepository->getAllDoctors();
    }

    public function getDoctorAvailability($doctorId) {
        return $this->doctorRepository->getDoctorAvailability($doctorId);
    }
}
