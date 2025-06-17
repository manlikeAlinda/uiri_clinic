<?= $this->extend('layouts/main') ?>
<?= helper(['form', 'visit']) ?>
<?= $this->section('content') ?>

<!-- Sidebar -->
<?= view('partials/sidenav') ?>
<main class="dashboard-main">
    <?= view('partials/topbar') ?>

    <div class="dashboard-main-body">
        <!-- Breadcrumb -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Manage Visits</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="<?= base_url('dashboard') ?>" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Manage Visits</li>
            </ul>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card basic-data-table">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Visits List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVisitModal">+ New Visit</button>
            </div>

            <div class="card-body">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Visit Date</th>
                            <th>Weight (kg)</th>
                            <th>Vitals</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visits as $visit): ?>
                            <tr>
                                <td><?= esc($visit['patient_name']) ?></td>
                                <td><?= esc($visit['doctor_name']) ?></td>
                                <td><?= date('Y-m-d', strtotime($visit['visit_date'])) ?></td>
                                <td><?= esc($visit['weight']) ?></td>
                                <td>
                                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="
                                            BP: <?= esc($visit['blood_pressure']) ?: '-' ?> |
                                            Pulse: <?= esc($visit['pulse']) ?: '-' ?> bpm |
                                            Temp: <?= esc($visit['temperature']) ?: '-' ?> °C |
                                            SpO₂: <?= esc($visit['sp02']) ?: '-' ?>% |
                                            Resp: <?= esc($visit['respiration_rate']) ?: '-' ?> bpm
                                            ">
                                        <iconify-icon icon="mdi:heart-pulse" class="text-danger"></iconify-icon>
                                    </button>
                                </td>


                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary view-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#visitUnifiedModal"
                                            data-id="<?= $visit['visit_id'] ?>"
                                            data-fetch-url="<?= base_url('visits/details/' . $visit['visit_id']) ?>"
                                            data-patient="<?= esc($visit['patient_name']) ?>"
                                            data-doctor="<?= esc($visit['doctor_name']) ?>"
                                            data-date="<?= esc($visit['visit_date']) ?>"
                                            data-weight="<?= esc($visit['weight']) ?>"
                                            data-bp="<?= esc($visit['blood_pressure']) ?>"
                                            data-pulse="<?= esc($visit['pulse']) ?>"
                                            data-temp="<?= esc($visit['temperature']) ?>"
                                            data-spo2="<?= esc($visit['sp02']) ?>"
                                            data-respiration="<?= esc($visit['respiration_rate']) ?>"
                                            data-category="<?= esc($visit['visit_category']) ?>"
                                            data-admission="<?= esc($visit['admission_time']) ?>"
                                            data-patient_complaints="<?= esc($visit['patient_complaints']) ?>"
                                            data-examination_notes="<?= esc($visit['examination_notes']) ?>"
                                            data-investigations="<?= esc($visit['investigations']) ?>"
                                            data-diagnosis="<?= esc($visit['diagnosis']) ?>">
                                            View
                                        </button>

                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="btn btn-info dropdown-item edit-visit-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editVisitModal"
                                                    data-id="<?= $visit['visit_id'] ?>"
                                                    data-patient_id="<?= $visit['patient_id'] ?>"
                                                    data-doctor_id="<?= $visit['doctor_id'] ?>"
                                                    data-date="<?= esc($visit['visit_date']) ?>"
                                                    data-weight="<?= esc($visit['weight']) ?>"
                                                    data-bp="<?= esc($visit['blood_pressure']) ?>"
                                                    data-pulse="<?= esc($visit['pulse']) ?>"
                                                    data-temp="<?= esc($visit['temperature']) ?>"
                                                    data-spo2="<?= esc($visit['sp02']) ?>"
                                                    data-respiration="<?= esc($visit['respiration_rate']) ?>"
                                                    data-category="<?= esc($visit['visit_category']) ?>"
                                                    data-admission="<?= esc($visit['admission_time']) ?>"
                                                    data-patient_complaints="<?= esc($visit['patient_complaints']) ?>"
                                                    data-examination_notes="<?= esc($visit['examination_notes']) ?>"
                                                    data-investigations="<?= esc($visit['investigations']) ?>"
                                                    data-diagnosis="<?= esc($visit['diagnosis']) ?>">
                                                    Edit Visit
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button"
                                                    class="btn btn-warning btn-sm edit-details-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#visitDetailsModal"
                                                    data-visit-id="<?= $visit['visit_id'] ?>">
                                                    Treatment & Outcomes
                                                </button>
                                            </li>
                                            <li>
                                                <button class="btn btn-danger dropdown-item delete-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteVisitModal"
                                                    data-id="<?= esc($visit['visit_id']) ?>"
                                                    data-patient="<?= esc($visit['patient_name']) ?>">
                                                    Delete Visit
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Visit Modal -->
        <div class="modal fade" id="addVisitModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="<?= base_url('visits/store') ?>" method="post" novalidate>
                    <?= csrf_field() ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Visit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="stepper">
                                <ul class="nav nav-pills mb-3" id="visitSteps" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link active" id="step1-tab" data-bs-toggle="pill" data-bs-target="#step1">
                                            Basic Info
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link" id="step2-tab" data-bs-toggle="pill" data-bs-target="#step2">
                                            Vitals
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link" id="step3-tab" data-bs-toggle="pill" data-bs-target="#step3">
                                            Assessment
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content p-2" id="visitStepsContent">
                                    <!-- Step 1: Basic Info -->
                                    <div class="tab-pane fade show active" id="step1" role="tabpanel">
                                        <div class="row gx-3 gy-3">
                                            <div class="col-md-6">
                                                <label for="addPatientId" class="form-label">Patient</label>
                                                <select id="addPatientId" name="patient_id" class="form-select" required>
                                                    <option value="">Select Patient</option>
                                                    <?php foreach ($patients as $p): ?>
                                                        <option value="<?= $p['patient_id'] ?>"><?= esc($p['first_name'] . ' ' . $p['last_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <?= validation_show_error('patient_id', '<div class="text-danger small">', '</div>') ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="addDoctorId" class="form-label">Doctor</label>
                                                <select id="addDoctorId" name="doctor_id" class="form-select" required>
                                                    <option value="">Select Doctor</option>
                                                    <?php foreach ($doctors as $d): ?>
                                                        <option value="<?= $d['doctor_id'] ?>"><?= esc($d['first_name'] . ' ' . $d['last_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <?= validation_show_error('doctor_id', '<div class="text-danger small">', '</div>') ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="addVisitDate" class="form-label">Visit Date</label>
                                                <input id="addVisitDate" type="date" name="visit_date" class="form-control" required>
                                                <?= validation_show_error('visit_date', '<div class="text-danger small">', '</div>') ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="addVisitCategory" class="form-label">Category</label>
                                                <select id="addVisitCategory" name="visit_category" class="form-select" required>
                                                    <option value="">Select</option>
                                                    <option value="in-patient">In-Patient</option>
                                                    <option value="out-patient">Out-Patient</option>
                                                </select>
                                                <?= validation_show_error('visit_category', '<div class="text-danger small">', '</div>') ?>
                                            </div>
                                            <div class="col-md-6" id="addAdmissionContainer" style="display:none;">
                                                <label for="addAdmissionTime" class="form-label">Admission Time</label>
                                                <input id="addAdmissionTime" type="datetime-local" name="admission_time" class="form-control">
                                                <?= validation_show_error('admission_time', '<div class="text-danger small">', '</div>') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 2: Vitals -->
                                    <div class="tab-pane fade" id="step2" role="tabpanel">
                                        <div class="row gx-3 gy-3">
                                            <div class="col-md-6">
                                                <label for="weight" class="form-label">Weight (kg)</label>
                                                <input id="weight" name="weight" type="number" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="blood_pressure" class="form-label">Blood Pressure</label>
                                                <input id="blood_pressure" name="blood_pressure" type="text" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="pulse" class="form-label">Pulse</label>
                                                <input id="pulse" name="pulse" type="number" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="temperature" class="form-label">Temperature</label>
                                                <input id="temperature" name="temperature" type="number" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="sp02" class="form-label">SpO₂ (%)</label>
                                                <input id="sp02" name="sp02" type="number" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="respiration_rate" class="form-label">Respiration Rate</label>
                                                <input id="respiration_rate" name="respiration_rate" type="number" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 3: Assessment -->
                                    <div class="tab-pane fade" id="step3" role="tabpanel">
                                        <div class="row gx-3 gy-3">
                                            <div class="col-md-6">
                                                <label for="patient_complaints" class="form-label">Patient Complaints</label>
                                                <textarea id="patient_complaints" name="patient_complaints" rows="2" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="examination_notes" class="form-label">Examination Notes</label>
                                                <textarea id="examination_notes" name="examination_notes" rows="2" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="investigations" class="form-label">Investigations</label>
                                                <textarea id="investigations" name="investigations" rows="2" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="diagnosis" class="form-label">Diagnosis</label>
                                                <textarea id="diagnosis" name="diagnosis" rows="2" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-outline-secondary" onclick="bootstrap.Tab.getInstance(document.querySelector('#step1-tab')).show()">Back</button>
                                    <button type="button" class="btn btn-outline-primary" onclick="bootstrap.Tab.getInstance(document.querySelector('#step2-tab')).show()">Next</button>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save Visit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Visit Modal -->
        <div class="modal fade" id="editVisitModal" tabindex="-1" aria-labelledby="editVisitModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="editVisitForm" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="visit_id" id="editVisitId">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Visit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Step Tabs -->
                            <ul class="nav nav-pills mb-3" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" data-bs-toggle="pill" data-bs-target="#editStep1">
                                        1. Info
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" data-bs-toggle="pill" data-bs-target="#editStep2">
                                        2. Vitals
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" data-bs-toggle="pill" data-bs-target="#editStep3">
                                        3. Assessment
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content p-3 bg-light border rounded">
                                <!-- Step 1: Info -->
                                <div class="tab-pane fade show active" id="editStep1">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="editPatientId" class="form-label">Patient</label>
                                            <select id="editPatientId" name="patient_id" class="form-select" required>
                                                <option value="">Select Patient</option>
                                                <?php foreach ($patients as $patient): ?>
                                                    <option value="<?= esc($patient['patient_id']) ?>">
                                                        <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editDoctorId" class="form-label">Doctor</label>
                                            <select id="editDoctorId" name="doctor_id" class="form-select" required>
                                                <option value="">Select Doctor</option>
                                                <?php foreach ($doctors as $doctor): ?>
                                                    <option value="<?= esc($doctor['doctor_id']) ?>">
                                                        <?= esc($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editVisitDate" class="form-label">Visit Date</label>
                                            <input type="date" id="editVisitDate" name="visit_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editVisitCategory" class="form-label">Category</label>
                                            <select id="editVisitCategory" name="visit_category" class="form-select" required>
                                                <option value="in-patient">In-Patient</option>
                                                <option value="out-patient">Out-Patient</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6" id="editAdmissionFields" style="display: none;">
                                            <label for="editAdmissionTime" class="form-label">Admission Time</label>
                                            <input type="datetime-local" id="editAdmissionTime" name="admission_time" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Vitals -->
                                <div class="tab-pane fade" id="editStep2">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="editWeight" class="form-label">Weight (kg)</label>
                                            <input type="number" step="0.1" id="editWeight" name="weight" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editBP" class="form-label">Blood Pressure</label>
                                            <input type="text" id="editBP" name="blood_pressure" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editPulse" class="form-label">Pulse</label>
                                            <input type="number" id="editPulse" name="pulse" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editTemp" class="form-label">Temperature (°C)</label>
                                            <input type="number" step="0.1" id="editTemp" name="temperature" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editSpO2" class="form-label">SpO₂ (%)</label>
                                            <input type="number" step="0.1" id="editSpO2" name="sp02" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editRespiration" class="form-label">Respiration Rate</label>
                                            <input type="number" id="editRespiration" name="respiration_rate" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Assessment -->
                                <div class="tab-pane fade" id="editStep3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="editComplaints" class="form-label">Patient Complaints</label>
                                            <textarea id="editComplaints" name="patient_complaints" rows="2" class="form-control" required></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editExamination" class="form-label">Examination Notes</label>
                                            <textarea id="editExamination" name="examination_notes" rows="2" class="form-control"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editInvestigations" class="form-label">Investigations</label>
                                            <textarea id="editInvestigations" name="investigations" rows="2" class="form-control"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editDiagnosis" class="form-label">Diagnosis</label>
                                            <textarea id="editDiagnosis" name="diagnosis" rows="2" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end tab-content -->
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update Visit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- //unified visit View -->
        <div class="modal fade" id="visitUnifiedModal" tabindex="-1" aria-labelledby="visitUnifiedModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Visit Summary & Actions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overviewTab">Overview</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#prescriptionsTab">Prescriptions</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#suppliesTab">Supplies</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#outcomesTab">Outcome</button></li>
                        </ul>

                        <div class="tab-content">
                            <!-- Overview -->
                            <div class="tab-pane fade show active" id="overviewTab" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-6"><strong>Patient:</strong> <span id="uPatient"></span></div>
                                    <div class="col-md-6"><strong>Doctor:</strong> <span id="uDoctor"></span></div>
                                    <div class="col-md-6"><strong>Date:</strong> <span id="uDate"></span></div>
                                    <div class="col-md-6"><strong>Category:</strong> <span id="uCategory"></span></div>
                                    <div class="col-md-6"><strong>Weight:</strong> <span id="uWeight"></span> kg</div>
                                    <div class="col-md-6"><strong>Blood Pressure:</strong> <span id="uBP"></span></div>
                                    <div class="col-md-6"><strong>Pulse:</strong> <span id="uPulse"></span> bpm</div>
                                    <div class="col-md-6"><strong>Temperature:</strong> <span id="uTemp"></span> °C</div>
                                    <div class="col-md-6"><strong>SpO₂:</strong> <span id="uSpO2"></span> %</div>
                                    <div class="col-md-6"><strong>Respiration:</strong> <span id="uRespiration"></span> bpm</div>
                                    <div class="col-md-12"><strong>Complaints:</strong> <span id="uComplaints"></span></div>
                                    <div class="col-md-12"><strong>Examination Notes:</strong> <span id="uExamination"></span></div>
                                    <div class="col-md-12"><strong>Investigations:</strong> <span id="uInvestigations"></span></div>
                                    <div class="col-md-12"><strong>Diagnosis:</strong> <span id="uDiagnosis"></span></div>
                                    <div class="col-md-12"><strong>Admission:</strong> <span id="uAdmission"></span></div>
                                </div>
                            </div>

                            <!-- Prescriptions Tab -->
                            <div class="tab-pane fade" id="prescriptionsTab" role="tabpanel">
                                <ul id="uPrescriptions" class="list-group"></ul>
                            </div>

                            <!-- Supplies Tab -->
                            <div class="tab-pane fade" id="suppliesTab" role="tabpanel">
                                <ul id="uSupplies" class="list-group"></ul>
                            </div>

                            <!-- Outcome Tab -->
                            <div class="tab-pane fade" id="outcomesTab" role="tabpanel">
                                <p><strong>Outcome:</strong> <span id="uOutcome"></span></p>
                                <p><strong>Treatment Notes:</strong> <span id="uTreatmentNotes"></span></p>
                                <p><strong>Discharge Time:</strong> <span id="uDischargeTime"></span></p>
                                <p><strong>Referral Reason:</strong> <span id="uReferralReason"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Visit Modal -->
        <div class="modal fade" id="deleteVisitModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="<?= base_url('visits/delete') ?>" method="post">
                    <input type="hidden" name="visit_id" id="deleteVisitId">
                    <?= csrf_field() ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Visit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                Are you sure you want to delete the visit for
                                <strong id="deletePatientName">____</strong>?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Visit Details Modal -->
        <div class="modal fade" id="visitDetailsModal" tabindex="-1" aria-labelledby="visitDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Visit Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Nav Tabs -->
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item"><button type="button" class="nav-link active" id="prescriptions-tab" data-bs-toggle="tab" data-bs-target="#prescriptions">Prescriptions</button></li>
                            <li class="nav-item"><button type="button" class="nav-link" id="supplies-tab" data-bs-toggle="tab" data-bs-target="#supplies">Supplies</button></li>
                            <li class="nav-item"><button type="button" class="nav-link" id="outcomes-tab" data-bs-toggle="tab" data-bs-target="#outcomes">Outcomes</button></li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">

                            <!-- ✅ PRESCRIPTIONS TAB -->
                            <div class="tab-pane fade show active" id="prescriptions" role="tabpanel">
                                <?php foreach ($prescriptions as $p): ?>
                                    <div class="card mb-2" id="prescription-card-<?= $p['prescription_id'] ?>">
                                        <div class="card-body">
                                            <strong><?= esc($p['drug_name']) ?></strong><br>
                                            Dosage: <?= esc($p['dosage']) ?> | Qty: <?= esc($p['quantity']) ?><br>
                                            Duration: <?= esc($p['duration']) ?> days | Route: <?= esc($p['route']) ?><br>
                                            <?= $p['instructions'] ? "Instructions: " . esc($p['instructions']) : '' ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Add Form -->
                                <?= view('partials/forms/add_prescription_form') ?>
                            </div>

                            <!-- ✅ SUPPLIES TAB -->
                            <div class="tab-pane fade" id="supplies" role="tabpanel">
                                <?php foreach ($supplies_used as $s): ?>
                                    <div class="card mb-2" id="supply-card-<?= $s['supply_usage_id'] ?>">
                                        <div class="card-body">
                                            <strong><?= esc($s['supply_name']) ?></strong><br>
                                            Quantity: <?= esc($s['quantity_used']) ?> | Type: <?= esc($s['usage_type']) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Add Form -->
                                <?= view('partials/forms/add_supply_form') ?>
                            </div>

                            <!-- ✅ OUTCOMES TAB -->
                            <div class="tab-pane fade" id="outcomes" role="tabpanel">
                                <?php if ($outcome): ?>
                                    <div class="card mb-2" id="outcome-card-<?= $outcome['outcome_id'] ?>">
                                        <div class="card-body">
                                            <strong>Outcome:</strong> <?= esc($outcome['summary']) ?><br>
                                            <strong>Treatment:</strong> <?= esc($outcome['treatment']) ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?= view('partials/forms/add_outcome_form') ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const getById = id => document.getElementById(id);
                const queryAll = (selector) => document.querySelectorAll(selector);

                // 1. Tooltip Initialization
                queryAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

                // 2. Generic Modal Utility
                function openModal(modalId) {
                    document.querySelectorAll('.modal.show').forEach(open => bootstrap.Modal.getInstance(open)?.hide());
                    const el = getById(modalId);
                    if (el) {
                        el.querySelector('.modal-body')?.scrollTo(0, 0);
                        bootstrap.Modal.getOrCreateInstance(el).show();
                    }
                }

                // 3. Toggle Add Fields for In-Patients
                const toggleAdmissionVisibility = (selectEl, containerId) => {
                    const cont = getById(containerId);
                    if (selectEl && cont) {
                        cont.style.display = selectEl.value === 'in-patient' ? 'block' : 'none';
                    }
                };
                const addCategory = getById('addVisitCategory');
                addCategory?.addEventListener('change', () => toggleAdmissionVisibility(addCategory, 'addAdmissionContainer'));
                toggleAdmissionVisibility(addCategory, 'addAdmissionContainer');

                // 4. Delete Visit Setup
                const deleteVisitId = getById('deleteVisitId');
                const deletePatientName = getById('deletePatientName');
                queryAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        deleteVisitId.value = btn.dataset.id || '';
                        deletePatientName.textContent = btn.dataset.patient || 'Unknown';
                    });
                });

                // 5. View Visit Details
                queryAll('.view-btn').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const url = btn.dataset.fetchUrl;
                        const fields = [
                            'patient', 'doctor', 'date', 'category', 'weight', 'bp', 'pulse',
                            'temp', 'spo2', 'respiration', 'patient_complaints', 'examination_notes',
                            'investigations', 'diagnosis', 'admission'
                        ];

                        fields.forEach(f => {
                            const el = getById(`u${f.charAt(0).toUpperCase() + f.slice(1)}`);
                            if (el) el.textContent = btn.dataset[f] || '-';
                        });

                        getById('uPrescriptions').innerHTML = '<div class="text-muted">Loading...</div>';
                        getById('uSupplies').innerHTML = '';
                        getById('uOutcome').textContent = '';

                        try {
                            const res = await fetch(url);
                            const data = await res.json();
                            getById('uPrescriptions').innerHTML = (data.prescriptions || []).map(p =>
                                `<li class="list-group-item">${p.drug_name} — ${p.dosage}, ${p.duration} days</li>`
                            ).join('');
                            getById('uSupplies').innerHTML = (data.supplies || []).map(s =>
                                `<li class="list-group-item">${s.supply_name} — ${s.quantity_used} (${s.usage_type})</li>`
                            ).join('');
                            const outcome = data.outcome || {};
                            getById('uOutcome').textContent = outcome.outcome || '-';
                            getById('uTreatmentNotes').textContent = outcome.treatment_notes || '-';
                            getById('uDischargeTime').textContent = outcome.discharge_time || '-';
                            getById('uReferralReason').textContent = outcome.referral_reason || '-';
                        } catch (err) {
                            console.error(err);
                            getById('uPrescriptions').innerHTML = '<li class="text-danger">Error loading data.</li>';
                        }

                        openModal('visitUnifiedModal');
                    });
                });

                // 6. Edit Visit Handling
                queryAll('.edit-visit-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const form = getById('editVisitForm');
                        const setVal = (id, val) => {
                            const el = getById(id);
                            if (el) el.value = val || '';
                        };
                        const id = btn.dataset.id;

                        form.action = `/visits/update/${id}`;
                        setVal('editVisitId', id);
                        setVal('editPatientId', btn.dataset.patient_id);
                        setVal('editDoctorId', btn.dataset.doctor_id);
                        setVal('editVisitDate', btn.dataset.date?.split(' ')[0]);
                        setVal('editWeight', btn.dataset.weight);
                        setVal('editBP', btn.dataset.bp);
                        setVal('editPulse', btn.dataset.pulse);
                        setVal('editTemp', btn.dataset.temp);
                        setVal('editSpO2', btn.dataset.spo2);
                        setVal('editRespiration', btn.dataset.respiration);
                        setVal('editComplaints', btn.dataset.patient_complaints);
                        setVal('editExamination', btn.dataset.examination_notes);
                        setVal('editInvestigations', btn.dataset.investigations);
                        setVal('editDiagnosis', btn.dataset.diagnosis);

                        const cat = btn.dataset.category;
                        setVal('editVisitCategory', cat);
                        const admissionGroup = getById('editAdmissionFields');
                        if (cat === 'in-patient') {
                            admissionGroup.style.display = 'flex';
                            setVal('editAdmissionTime', btn.dataset.admission);
                        } else {
                            admissionGroup.style.display = 'none';
                            setVal('editAdmissionTime', '');
                        }

                        openModal('editVisitModal');
                    });
                });

                // 7. Toggle Edit Admission Fields
                const catToggle = getById('editVisitCategory');
                catToggle?.addEventListener('change', () => {
                    const group = getById('editAdmissionFields');
                    group.style.display = catToggle.value === 'in-patient' ? 'flex' : 'none';
                    if (catToggle.value !== 'in-patient') getById('editAdmissionTime').value = '';
                });

                // 8. Outcome Logic
                const outcomeSelect = getById('outcome');
                if (outcomeSelect) {
                    const referral = getById('addReferralGroup');
                    const discharge = getById('addDischargeContainer');
                    const condition = getById('addDischargeConditionGroup');

                    const toggleOutcomeFields = () => {
                        if (referral) referral.style.display = outcomeSelect.value === 'Referred' ? 'block' : 'none';
                        if (discharge) discharge.style.display = outcomeSelect.value === 'Discharged' ? 'block' : 'none';
                        if (condition) condition.style.display = outcomeSelect.value === 'Discharged' ? 'block' : 'none';
                    };

                    outcomeSelect.addEventListener('change', toggleOutcomeFields);
                    toggleOutcomeFields();
                }

                // Attach handler to every button that opens the Visit Details Modal
                document.querySelectorAll('[data-bs-target="#visitDetailsModal"]').forEach(button => {
                    button.addEventListener('click', () => {
                        const visitId = button.getAttribute('data-visit-id');
                        document.querySelectorAll('.set-visit-id').forEach(input => {
                            input.value = visitId;
                        });
                    });
                });

                async function refreshCsrfToken() {
                    try {
                        const res = await fetch("<?= site_url('getCsrfToken') ?>");
                        const data = await res.json();
                        const tokenName = "<?= csrf_token() ?>";
                        document.querySelectorAll('input[name="' + tokenName + '"]').forEach(el => {
                            el.value = data.token;
                        });
                    } catch (err) {
                        console.error("Failed to refresh CSRF token", err);
                    }
                }

                // Refresh token when modal is shown
                const modal = document.getElementById('visitDetailsModal');
                if (modal) {
                    modal.addEventListener('show.bs.modal', () => {
                        refreshCsrfToken();
                    });
                }

                // 9. Modal Cleanup
                queryAll('.modal').forEach(modal => {
                    modal.addEventListener('hidden.bs.modal', () => {
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        queryAll('.modal-backdrop').forEach(el => el.remove());
                    });
                });
            });
        </script>

    </div>
</main>

<?= $this->endSection() ?>