<?php

namespace App\Controllers;

// use App\Models\VisitModel;
// use App\Models\DrugModel;
use Config\Database;

class DashboardController extends BaseController
{
    public function index()
    {
        $db   = Database::connect();
        $req  = service('request');
        $period = $req->getGet('period') ?? 'month'; // 'week' or 'month'

        // --------- 1. No. of admissions ----------
        $admissions = (int) $db->table('visits')
            ->where('admission_time IS NOT NULL', null, false)
            ->countAllResults();

        // --------- 2. No. of conditions diagnosed (total) ----------
        $conditionsTotal = (int) $db->table('visits')
            ->where('diagnosis IS NOT NULL', null, false)
            ->where("diagnosis <> ''", null, false)
            ->countAllResults();

        // --------- 2b. Conditions diagnosed per period (week/month) ----------
        if ($period === 'week') {
            $condRows = $db->query("
                SELECT YEARWEEK(visit_date, 1) AS grp,
                       CONCAT('W', WEEK(visit_date, 1), ' ', DATE_FORMAT(visit_date,'%b')) AS label,
                       COUNT(*) AS c
                FROM visits
                WHERE diagnosis IS NOT NULL AND diagnosis <> ''
                GROUP BY grp, label
                ORDER BY grp ASC
            ")->getResultArray();
        } else {
            $condRows = $db->query("
                SELECT DATE_FORMAT(visit_date,'%Y-%m') AS grp,
                       DATE_FORMAT(visit_date,'%b %y')   AS label,
                       COUNT(*) AS c
                FROM visits
                WHERE diagnosis IS NOT NULL AND diagnosis <> ''
                GROUP BY grp, label
                ORDER BY grp ASC
            ")->getResultArray();
        }
        $conditionsPerPeriod = array_values($condRows); // keep as [{label,c},...]

        // --------- 3. No. of out-patients ----------
        $outpatients = (int) $db->table('visits')
            ->where('visit_category', 'out-patient')
            ->countAllResults();

        // --------- 4. Drugs inventory summary ----------
        $inv = $db->table('drugs')
            ->select('COUNT(*) AS items, SUM(quantity_in_stock) AS qty', false)
            ->get()->getRow();
        $inventory = [
            'items' => (int) ($inv->items ?? 0),
            'qty'   => (int) ($inv->qty   ?? 0),
        ];

        // --------- 5. No. of patients referred ----------
        $referred = (int) $db->table('visit_outcomes')
            ->where('outcome', 'Referred')
            ->countAllResults();

        // --------- 6. Total admissions + outpatients ----------
        $totalAdmitOut = $admissions + $outpatients;

        // --------- 7. Most observed conditions (Top 10) ----------
        $topConditions = $db->table('visits')
            ->select('diagnosis, COUNT(*) AS c')
            ->where('diagnosis IS NOT NULL', null, false)
            ->where("diagnosis <> ''", null, false)
            ->groupBy('diagnosis')
            ->orderBy('c', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        // --------- 8. Patients per month (last 12) ----------
        $patientsPerMonth = $db->table('visits')
            ->select("DATE_FORMAT(visit_date,'%b %y') AS label, COUNT(*) AS c", false)
            ->where('visit_date >=', date('Y-m-d', strtotime('-12 months')))
            ->groupBy('label')
            ->orderBy("MIN(visit_date)", 'ASC', false)
            ->get()->getResultArray();

        // Patients per month split (Admissions vs Outpatients)
        $patientsByMonth = $db->table('visits')
            ->select("DATE_FORMAT(visit_date,'%Y-%m') ym,
              DATE_FORMAT(visit_date,'%b')  label,
              SUM(visit_category='in-patient')  AS admits,
              SUM(visit_category='out-patient') AS outs", false)
            ->where('visit_date >=', date('Y-m-01', strtotime('-11 months')))
            ->groupBy('ym, label')
            ->orderBy('ym', 'ASC')
            ->get()->getResultArray();

        $dataForView['patientsByMonth'] = $patientsByMonth;


        return view('dashboard', [
            'admissions'          => $admissions,
            'conditionsTotal'     => $conditionsTotal,
            'conditionsPerPeriod' => $conditionsPerPeriod,
            'outpatients'         => $outpatients,
            'inventory'           => $inventory,
            'referred'            => $referred,
            'totalAdmitOut'       => $totalAdmitOut,
            'topConditions'       => $topConditions,
            'patientsPerMonth'    => $patientsPerMonth,
            'patientsByMonth'     => $patientsByMonth,   // ← ADD THIS
            'period'              => $period,
        ]);
    }
}
