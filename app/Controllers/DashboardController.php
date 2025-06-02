<?php

namespace App\Controllers;
use App\Models\VisitModel;
use App\Models\TreatmentPlanModel;
use App\Models\DrugPrescriptionModel;
use App\Models\DrugModel;
use App\Models\EquipmentUsageModel;
use App\Models\SupplyUsageModel;
use App\Models\PatientModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $doctor_id = session()->get('doctor_id') ?? 1; // Default to doctor 1 for mock

        // FAKE STUB LOGIC: Replace with real query builders in your models

        // 1. Today's Scheduled Visits
        $scheduledVisitsToday = 3;

        // 2. In Progress Cases
        $inProgressCases = 2;

        // 3. Recently Completed Visits
        $recentCompletedVisits = [
            ['patient' => 'John Doe', 'date' => '2024-05-25', 'diagnosis' => 'Flu', 'notes' => 'Prescribed rest.'],
            ['patient' => 'Alice Smith', 'date' => '2024-05-24', 'diagnosis' => 'Malaria', 'notes' => 'Antimalarial course.'],
            ['patient' => 'Mark Bright', 'date' => '2024-05-22', 'diagnosis' => 'Allergy', 'notes' => 'Administered antihistamines.'],
        ];

        // 4. Active Patients Under Care
        $activePatients = 7;

        // 5. Prescriptions Breakdown
        $prescriptionTotal = 42;
        $prescriptionBreakdown = [
            ['drug' => 'Paracetamol', 'count' => 15],
            ['drug' => 'Amoxicillin', 'count' => 10],
            ['drug' => 'Ibuprofen', 'count' => 7],
            ['drug' => 'Vitamin C', 'count' => 5],
            ['drug' => 'Omeprazole', 'count' => 5],
        ];

        // 6. Recent Diagnoses
        $recentDiagnoses = [
            ['diagnosis' => 'Typhoid', 'notes' => 'Prescribed Ciprofloxacin'],
            ['diagnosis' => 'Flu', 'notes' => 'Rest and fluids'],
            ['diagnosis' => 'Asthma', 'notes' => 'Inhaler issued'],
            ['diagnosis' => 'Headache', 'notes' => 'Painkillers recommended'],
            ['diagnosis' => 'Allergy', 'notes' => 'Monitored reaction'],
        ];

        // 7. Drugs in Stock (Most used)
        $topDrugs = [
            ['name' => 'Paracetamol', 'stock' => 120],
            ['name' => 'Amoxicillin', 'stock' => 90],
            ['name' => 'Ibuprofen', 'stock' => 60],
            ['name' => 'Cetrizine', 'stock' => 45],
            ['name' => 'Vitamin C', 'stock' => 30],
        ];

        // 8. Equipment Used
        $equipmentUsed = [
            ['name' => 'Stethoscope', 'used' => 12],
            ['name' => 'Thermometer', 'used' => 9],
            ['name' => 'Blood Pressure Monitor', 'used' => 5],
        ];

        // 9. Supply Usage (Pie Chart)
        $supplyUsage = [
            ['name' => 'Gloves', 'used' => 100],
            ['name' => 'Syringes', 'used' => 80],
            ['name' => 'Cotton', 'used' => 50],
        ];

        // 10. Chart Data
        $visitStatusChart = [
            'Scheduled' => 5,
            'In Progress' => 2,
            'Completed' => 8
        ];

        $patientsOverTime = [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'data' => [2, 3, 5, 4, 6, 2, 1]
        ];

        $visitTypes = [
            'Follow-up' => 6,
            'Emergency' => 4,
            'Routine Check' => 5
        ];

        return view('dashboard/index', [
            'scheduledVisitsToday' => $scheduledVisitsToday,
            'inProgressCases' => $inProgressCases,
            'recentCompletedVisits' => $recentCompletedVisits,
            'activePatients' => $activePatients,
            'prescriptionTotal' => $prescriptionTotal,
            'prescriptionBreakdown' => $prescriptionBreakdown,
            'recentDiagnoses' => $recentDiagnoses,
            'topDrugs' => $topDrugs,
            'equipmentUsed' => $equipmentUsed,
            'supplyUsage' => $supplyUsage,
            'visitStatusChart' => $visitStatusChart,
            'patientsOverTime' => $patientsOverTime,
            'visitTypes' => $visitTypes,
        ]);
    }
}
