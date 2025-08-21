<?php

namespace App\Controllers;

use Config\Database;
use App\Models\PatientModel;

class ReportsController extends BaseController
{
    public function index()
    {
        $db         = Database::connect();
        $from       = $this->request->getGet('from')   ?? date('Y-m-01');
        $to         = $this->request->getGet('to')     ?? date('Y-m-d');
        $period     = $this->request->getGet('period') ?? 'month';
        $patient_id = $this->request->getGet('patient_id');

        // 1) Patients for dropdown
        $patientModel = new PatientModel();
        $patients     = $patientModel->orderBy('last_name', 'asc')->findAll();

        // 2) Load selected patient
        $patient = $patient_id
            ? $patientModel->find($patient_id)
            : null;

        // 3) Most recent visit in range
        $visit = null;
        if ($patient_id) {
            $visit = $db->table('visits')
                ->where('patient_id', $patient_id)
                ->where('visit_date >=', $from)
                ->where('visit_date <=', $to)
                ->orderBy('visit_date', 'DESC')
                ->get()
                ->getRowArray();
        }

        // ─────────────────────────────────────────────────────────
        // 3) Load the patient’s main visit in the date range
        //    (including doctor’s name)
        // ─────────────────────────────────────────────────────────
        $visit = null;
        if ($patient_id) {
            $visit = $db->table('visits v')
                ->select('
            v.*,
            d.first_name AS doctor_first,
            d.last_name  AS doctor_last
        ')
                ->join('doctors d', 'd.doctor_id = v.doctor_id', 'left')
                ->where('v.patient_id',   $patient_id)
                ->where('v.visit_date>=', $from)
                ->where('v.visit_date<=', $to)
                ->orderBy('v.visit_date', 'DESC')
                ->get()
                ->getRowArray();

            if ($visit) {
                // consolidate into one field for the view
                $visit['doctor_name'] = trim($visit['doctor_first'] . ' ' . $visit['doctor_last']);
            }
        }

        // 4) Pull “vitals” and text fields from visits row
        $vitals = [];
        $complaints     = [];
        $examNotes      = '';
        $diagnoses      = [];
        $investigations = [];
        if ($visit) {
            // direct vitals fields
            $vitals = [
                'weight'    => $visit['weight'],
                'bp'        => $visit['blood_pressure'],
                'pulse'     => $visit['pulse'],
                'temp'      => $visit['temperature'],
                'sp02'      => $visit['sp02'],
                'resp_rate' => $visit['respiration_rate'],
            ];
            // split multiline text into arrays
            $complaints     = $visit['patient_complaints']
                ? preg_split('/\r\n|\n/', $visit['patient_complaints'])
                : [];
            $examNotes      = $visit['examination_notes'] ?? '';
            $diagnoses      = $visit['diagnosis']
                ? preg_split('/\r\n|\n/', $visit['diagnosis'])
                : [];
            $investigations = $visit['investigations']
                ? preg_split('/\r\n|\n/', $visit['investigations'])
                : [];
        }

        // 5) Prescriptions, Supplies & Outcome
        $prescriptions = $visit
            ? $db->table('visit_prescriptions vp')
            ->select('d.name AS drug, vp.dosage, vp.quantity, vp.duration, vp.route, vp.instructions')
            ->join('drugs d', 'd.drug_id=vp.drug_id')
            ->where('vp.visit_id', $visit['visit_id'])
            ->get()
            ->getResultArray()
            : [];

        $supplies = $visit
            ? $db->table('visit_supplies vs')
            ->select('s.name, vs.quantity_used AS qty')
            ->join('supplies s', 's.supply_id=vs.supply_id')
            ->where('vs.visit_id', $visit['visit_id'])
            ->get()
            ->getResultArray()
            : [];

        $outcome = $visit
            ? (array) $db->table('visit_outcomes')
                ->where('visit_id', $visit['visit_id'])
                ->get()
                ->getRowArray()
            : [];

        // 6) Metrics helper
        $filterByPatient = function ($builder) use ($patient_id) {
            return $patient_id
                ? $builder->where('patient_id', $patient_id)
                : $builder;
        };

        // 6.1 Admissions
        $admissions = (int) $filterByPatient(
            $db->table('visits')
                ->where('admission_time IS NOT NULL', null, false)
                ->where('visit_date>=', $from)
                ->where('visit_date<=', $to)
        )->countAllResults();

        // 6.2 Conditions per period
        $conditionsPerPeriod = $this->countPerPeriod(
            $db,
            'visits',
            $from,
            $to,
            $period,
            "diagnosis IS NOT NULL AND diagnosis<>''",
            $patient_id
        );
        $conditionsTotal = array_sum(array_column($conditionsPerPeriod, 'c'));

        // 6.3 Out‑patients
        $outpatients = (int) $filterByPatient(
            $db->table('visits')
                ->where('visit_category', 'out-patient')
                ->where('visit_date>=', $from)
                ->where('visit_date<=', $to)
        )->countAllResults();

        // 6.4 Inventory (global)
        $inv = $db->table('drugs')
            ->select('SUM(quantity_in_stock) total_qty, COUNT(*) items')
            ->get()->getRow();
        $inventory = [
            'items' => (int)($inv->items ?? 0),
            'qty'   => (int)($inv->total_qty ?? 0),
        ];

        // 6.5 Referred
        $refBuilder = $db->table('visit_outcomes vo')
            ->join('visits v', 'v.visit_id=vo.visit_id', 'left')
            ->where('vo.outcome', 'Referred')
            ->where('v.visit_date>=', $from)
            ->where('v.visit_date<=', $to);
        if ($patient_id) {
            $refBuilder->where('v.patient_id', $patient_id);
        }
        $referred = (int)$refBuilder->countAllResults();

        // 6.6 Total admit + OP
        $totalAdmitOut = $admissions + $outpatients;

        // 6.7 Top conditions
        $topBuilder = $db->table('visits')
            ->select('diagnosis, COUNT(*) c')
            ->where('diagnosis IS NOT NULL', null, false)
            ->where("diagnosis<>''", null, false)
            ->where('visit_date>=', $from)
            ->where('visit_date<=', $to);
        if ($patient_id) {
            $topBuilder->where('patient_id', $patient_id);
        }
        $topConditions = $topBuilder
            ->groupBy('diagnosis')
            ->orderBy('c', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // 6.8 Patients per period
        $ppmBuilder = $db->table('visits')
            ->select("
                DATE_FORMAT(visit_date,'%Y-%m') ym,
                DATE_FORMAT(visit_date,'%b %y') label,
                COUNT(*) c
            ", false)
            ->where('visit_date>=', date('Y-m-d', strtotime("-12 months", strtotime($to))))
            ->where('visit_date<=', $to);
        if ($patient_id) {
            $ppmBuilder->where('patient_id', $patient_id);
        }
        $patientsPerMonth = $ppmBuilder
            ->groupBy('ym,label')
            ->orderBy('ym', 'ASC')
            ->get()
            ->getResultArray();

        // 7) Render
        return view('manage_reports', [
            'from'                 => $from,
            'to'                   => $to,
            'period'               => $period,
            'patient_id'           => $patient_id,
            'patients'             => $patients,
            'patient'              => $patient,
            'visit'                => $visit,
            'vitals'               => $vitals,
            'complaints'           => $complaints,
            'examNotes'            => $examNotes,
            'diagnoses'            => $diagnoses,
            'investigations'       => $investigations,
            'prescriptions'        => $prescriptions,
            'supplies'             => $supplies,
            'outcome'              => $outcome,
            'admissions'           => $admissions,
            'conditionsTotal'      => $conditionsTotal,
            'conditionsPerPeriod'  => $conditionsPerPeriod,
            'outpatients'          => $outpatients,
            'inventory'            => $inventory,
            'referred'             => $referred,
            'totalAdmitOut'        => $totalAdmitOut,
            'topConditions'        => $topConditions,
            'patientsPerMonth'     => $patientsPerMonth,
        ]);
    }

    /**
     * Count rows per week or month, filtering by patient if given.
     */
    private function countPerPeriod($db, $table, $from, $to, $period, $extraWhere = '', $patient_id = null)
    {
        $periodSql = $period === 'week'
            ? "YEARWEEK(visit_date) grp, CONCAT('W', WEEK(visit_date), ' ', DATE_FORMAT(visit_date,'%Y')) label"
            : "DATE_FORMAT(visit_date,'%Y-%m') grp, DATE_FORMAT(visit_date,'%b %y') label";

        $sql    = "SELECT $periodSql, COUNT(*) c
                   FROM $table
                   WHERE visit_date>=? AND visit_date<=?";
        $params = [$from, $to];

        if ($extraWhere) {
            $sql .= " AND ($extraWhere)";
        }
        if ($patient_id) {
            $sql      .= " AND patient_id=?";
            $params[] = $patient_id;
        }

        $sql .= " GROUP BY grp,label ORDER BY grp";
        return $db->query($sql, $params)->getResultArray();
    }
}
