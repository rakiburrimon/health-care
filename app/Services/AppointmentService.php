<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;

class AppointmentService {
    protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository) {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function createAppointment(array $data) {
        return $this->appointmentRepository->createAppointment($data);
    }

    public function getPatientAppointments($patientId) {
        return $this->appointmentRepository->getPatientAppointments($patientId);
    }

    public function getDoctorAppointments($doctorId) {
        return $this->appointmentRepository->getDoctorAppointments($doctorId);
    }
}
