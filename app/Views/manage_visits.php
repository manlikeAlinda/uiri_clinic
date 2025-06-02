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
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Visit Date</th>
                            <th>Weight</th>
                            <th>Vitals</th>
                            <th>Diagnosis</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visits as $visit): ?>
                            <tr>
                                <td><?= esc($visit['patient_name']) ?></td>
                                <td><?= esc($visit['doctor_name']) ?></td>
                                <td><?= esc($visit['visit_date']) ?></td>
                                <td><?= esc($visit['weight']) ?> kg</td>
                                <td>
                                    <small>BP: <?= esc($visit['blood_pressure']) ?: '-' ?><br></small>
                                    <small>HR: <?= esc($visit['heart_rate']) ?: '-' ?> bpm<br></small>
                                    <small>Temp: <?= esc($visit['temperature']) ?: '-' ?>°C</small>
                                </td>

                                <td><?= esc($visit['diagnosis']) ?></td>
                                <td>
                                    <span class="badge bg-<?= getStatusColor($visit['visit_status']) ?>">
                                        <?= esc($visit['visit_status']) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <!-- View -->
                                        <button class="btn btn-info btn-sm view-btn"
                                            style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewVisitModal"
                                            data-id="<?= $visit['visit_id'] ?>"
                                            data-patient="<?= esc($visit['patient_name']) ?>"
                                            data-doctor="<?= esc($visit['doctor_name']) ?>"
                                            data-date="<?= esc($visit['visit_date']) ?>"
                                            data-weight="<?= esc($visit['weight']) ?>"
                                            data-observations="<?= esc($visit['observations']) ?>"
                                            data-diagnosis="<?= esc($visit['diagnosis']) ?>"
                                            data-treatment="<?= esc($visit['treatment_notes']) ?>"
                                            data-initial_condition="<?= esc($visit['patient_condition']) ?>"
                                            data-admission="<?= esc($visit['admission_time']) ?>"
                                            data-discharge="<?= esc($visit['discharge_time']) ?>"
                                            data-referral="<?= esc($visit['referral_notes']) ?>">
                                            <iconify-icon icon="mdi:eye-outline" width="20" height="20" class="text-white"></iconify-icon>
                                        </button>

                                        <!-- Edit Visit -->
                                        <button type="button"
                                            class="w-32-px h-32-px btn btn-success rounded-circle d-inline-flex align-items-center justify-content-center edit-visit-btn"
                                            title="Edit Visit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editVisitModal"
                                            data-id="<?= $visit['visit_id'] ?>"
                                            data-patient_id="<?= $visit['patient_id'] ?>"
                                            data-doctor_id="<?= $visit['doctor_id'] ?>"
                                            data-date="<?= $visit['visit_date'] ?>"
                                            data-weight="<?= $visit['weight'] ?>"
                                            data-observations="<?= esc($visit['observations']) ?>"
                                            data-diagnosis="<?= esc($visit['diagnosis']) ?>"
                                            data-treatment="<?= esc($visit['treatment_notes']) ?>"
                                            data-initial_condition="<?= esc($visit['patient_condition']) ?>"
                                            data-admission="<?= esc($visit['admission_time']) ?>"
                                            data-discharge="<?= esc($visit['discharge_time']) ?>"
                                            data-referral="<?= esc($visit['referral_notes']) ?>"
                                            data-blood_pressure="<?= esc($visit['blood_pressure']) ?>"
                                            data-heart_rate="<?= esc($visit['heart_rate']) ?>"
                                            data-temperature="<?= esc($visit['temperature']) ?>"

                                            data-status="<?= esc($visit['visit_status']) ?>">
                                            <iconify-icon icon="mdi:pencil-outline" width="20" height="20" class="text-white"></iconify-icon>
                                        </button>

                                        <!-- Delete -->
                                        <button class="btn btn-danger btn-sm delete-btn"
                                            style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteVisitModal"
                                            data-id="<?= $visit['visit_id'] ?>">
                                            <iconify-icon icon="mingcute:delete-2-line" width="20" height="20" class="text-white"></iconify-icon>
                                        </button>

                                        <!-- Add Details -->
                                        <button class="btn btn-secondary btn-sm link-btn"
                                            style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#visitDetailsModal"
                                            data-id="<?= $visit['visit_id'] ?>"
                                            title="Record Visit Details">
                                            <iconify-icon icon="material-symbols:add-circle-outline" width="20" height="20" class="text-white"></iconify-icon>
                                        </button>

                                        <!-- Edit Details -->
                                        <button class="btn btn-warning btn-sm edit-details-btn"
                                            style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editVisitDetailsModal"
                                            data-id="<?= $visit['visit_id'] ?>"
                                            title="Edit Visit Details">
                                            <iconify-icon icon="mdi:clipboard-edit-outline" width="20" height="20" class="text-white"></iconify-icon>
                                        </button>
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
                <form action="<?= base_url('visits/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Visit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Patient</label>
                                        <select name="patient_id" class="form-select" required>
                                            <option value="">Select Patient</option>
                                            <?php foreach ($patients as $patient): ?>
                                                <option value="<?= $patient['patient_id'] ?>"><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= validation_show_error('patient_id', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Doctor</label>
                                        <select name="doctor_id" class="form-select" required>
                                            <option value="">Select Doctor</option>
                                            <?php foreach ($doctors as $doctor): ?>
                                                <option value="<?= $doctor['doctor_id'] ?>"><?= esc($doctor['first_name'] . ' ' . $doctor['last_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= validation_show_error('doctor_id', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Weight (kg)</label>
                                        <input type="number" step="0.1" name="weight" class="form-control">
                                        <?= validation_show_error('weight', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Blood Pressure (e.g. 120/80 mmHg)</label>
                                        <input type="text" name="blood_pressure" id="editBloodPressure" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>Heart Rate (bpm)</label>
                                        <input type="number" name="heart_rate" id="editHeartRate" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>Temperature (°C)</label>
                                        <input type="number" step="0.1" name="temperature" id="editTemperature" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Admission Time</label>
                                        <input type="datetime-local" name="admission_time" class="form-control">
                                        <?= validation_show_error('admission_time', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Discharge Time</label>
                                        <input type="datetime-local" name="discharge_time" class="form-control">
                                        <?= validation_show_error('discharge_time', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Observations</label>
                                        <textarea name="observations" class="form-control" rows="2"></textarea>
                                        <?= validation_show_error('observations', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Diagnosis</label>
                                        <textarea name="diagnosis" class="form-control" rows="2"></textarea>
                                        <?= validation_show_error('diagnosis', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Treatment Notes</label>
                                        <textarea name="treatment_notes" class="form-control" rows="2"></textarea>
                                        <?= validation_show_error('treatment_notes', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="visit_status" class="form-label">Visit Status</label>
                                        <select name="visit_status" class="form-select" id="editVisitStatus">
                                            <option value="Scheduled">Scheduled</option>
                                            <option value="In Progress">In Progress</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                        <?= validation_show_error('visit_status', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>



                                    <div class="mb-3">
                                        <label>Referral Notes</label>
                                        <textarea name="referral_notes" class="form-control" rows="2"></textarea>
                                        <?= validation_show_error('referral_notes', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
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
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('visits/update') ?>">
                    <?= csrf_field() ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Visit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="visit_id" id="editVisitId">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Patient</label>
                                    <select name="patient_id" id="editPatientId" class="form-select" required>
                                        <?php foreach ($patients as $patient): ?>
                                            <option value="<?= $patient['patient_id'] ?>"><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= validation_show_error('patient_id', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Doctor</label>
                                    <select name="doctor_id" id="editDoctorId" class="form-select" required>
                                        <?php foreach ($doctors as $doctor): ?>
                                            <option value="<?= $doctor['doctor_id'] ?>"><?= esc($doctor['first_name'] . ' ' . $doctor['last_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= validation_show_error('doctor_id', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Visit Date</label>
                                    <input type="date" name="visit_date" id="editVisitDate" class="form-control" required>
                                    <?= validation_show_error('visit_date', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="number" name="weight" id="editWeight" class="form-control">
                                    <?= validation_show_error('weight', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Observations</label>
                                    <textarea name="observations" id="editObservations" class="form-control" rows="2"></textarea>
                                    <?= validation_show_error('observations', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Diagnosis</label>
                                    <textarea name="diagnosis" id="editDiagnosis" class="form-control" rows="2"></textarea>
                                    <?= validation_show_error('diagnosis', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Treatment Notes</label>
                                    <textarea name="treatment_notes" id="editTreatment" class="form-control" rows="2"></textarea>
                                    <?= validation_show_error('treatment_notes', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>
                                <div class="col-12">
                                    <?php
                                    $visitIsPast = isset($visit['visit_date']) && strtotime($visit['visit_date']) <= strtotime(date('Y-m-d'));
                                    $requiredAttr = $visitIsPast ? 'required' : '';
                                    ?>
                                    <label class="form-label">
                                        Patient Condition
                                        <?php if ($visitIsPast): ?><span class="text-danger">*</span><?php endif; ?>
                                    </label>

                                    <select name="patient_condition" id="editPatientCondition" class="form-select" <?= $requiredAttr ?>>
                                        <option value="">Select Condition</option>
                                        <option value="Stable">Stable</option>
                                        <option value="Critical">Critical</option>
                                        <option value="Recovering">Recovering</option>
                                        <option value="Discharged">Discharged</option>
                                        <option value="Referred">Referred</option>
                                        <option value="Under Observation">Under Observation</option>
                                    </select>
                                    <?= validation_show_error('patient_condition', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Admission Time</label>
                                    <input type="datetime-local" name="admission_time" id="editAdmissionTime" class="form-control">
                                    <?= validation_show_error('admission_time', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Discharge Time</label>
                                    <input type="datetime-local" name="discharge_time" id="editDischargeTime" class="form-control">
                                    <?= validation_show_error('discharge_time', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>
                                <div class="mb-3" id="referralNotesGroup" style="display: none;">
                                    <label class="form-label">Referral Notes</label>
                                    <textarea name="referral_notes" id="editReferralNotes" class="form-control" rows="2"></textarea>
                                    <?= validation_show_error('referral_notes', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Visit Status</label>
                                    <select name="visit_status" id="editVisitStatus" class="form-select">
                                        <option value="Scheduled">Scheduled</option>
                                        <option value="In Progress">In Progress</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                    <?= validation_show_error('visit_status', '<div class="text-danger small mt-1">', '</div>') ?>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update Visit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- View Visit Modal -->
        <div class="modal fade" id="viewVisitModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Visit Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Patient:</strong> <span id="viewPatient"></span></p>
                        <p><strong>Doctor:</strong> <span id="viewDoctor"></span></p>
                        <p><strong>Date:</strong> <span id="viewDate"></span></p>
                        <p><strong>Weight:</strong> <span id="viewWeight"></span></p>
                        <p><strong>Blood Pressure:</strong> <span id="viewBP"></span></p>
                        <p><strong>Heart Rate:</strong> <span id="viewHR"></span></p>
                        <p><strong>Temperature:</strong> <span id="viewTemp"></span></p>

                        <p><strong>Observations:</strong> <span id="viewObservations"></span></p>
                        <p><strong>Diagnosis:</strong> <span id="viewDiagnosis"></span></p>
                        <p><strong>Treatment:</strong> <span id="viewTreatment"></span></p>
                        <p><strong>Patient Condition:</strong> <span id="viewPatientCondition"></span></p>
                        <p><strong>Admission Time:</strong> <span id="viewAdmission"></span></p>
                        <p><strong>Discharge Time:</strong> <span id="viewDischarge"></span></p>
                        <p><strong>Referral Notes:</strong> <span id="viewReferral"></span></p>

                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Visit Modal -->
        <div class="modal fade" id="deleteVisitModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="<?= base_url('visits/delete') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Visit</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this visit?
                            <input type="hidden" name="visit_id" id="deleteVisitId">
                        </div>
                        <div class="modal-footer"><button type="submit" class="btn btn-danger">Delete</button></div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Visit Details Modal (Prescriptions, Equipment, Supplies) -->
        <div class="modal fade" id="visitDetailsModal" tabindex="-1" aria-labelledby="visitDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Record Visit Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs mb-3" id="visitDetailTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="prescriptions-tab" data-bs-toggle="tab" data-bs-target="#prescriptions" type="button" role="tab">Prescriptions</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="equipment-tab" data-bs-toggle="tab" data-bs-target="#equipment" type="button" role="tab">Equipment Used</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="supplies-tab" data-bs-toggle="tab" data-bs-target="#supplies" type="button" role="tab">Supplies Used</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="visitDetailTabsContent">
                            <!-- Prescriptions Tab -->
                            <div class="tab-pane fade show active" id="prescriptions" role="tabpanel">
                                <form method="post" action="<?= base_url('visits/addDetails') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="visit_id" id="prescriptionVisitId" class="set-visit-id">
                                    <input type="hidden" name="type" value="prescription">
                                    <div class="mb-3">
                                        <label for="drug_id" class="form-label">Drug</label>
                                        <select class="form-select" name="drug_id" required>
                                            <?php foreach ($drugs as $drug): ?>
                                                <option value="<?= $drug['drug_id'] ?>"><?= esc($drug['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= validation_show_error('drug_id', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dosage" class="form-label">Dosage</label>
                                        <input type="text" name="dosage" class="form-control" required>
                                        <?= validation_show_error('dosage', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="duration" class="form-label">Duration</label>
                                        <input type="text" name="duration" class="form-control">
                                        <?= validation_show_error('duration', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Quantity (e.g Number of tablets)</label>
                                        <input type="number" name="quantity" class="form-control" min="1" required>
                                        <?= validation_show_error('quantity', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="instructions" class="form-label">Instructions</label>
                                        <textarea name="instructions" class="form-control"></textarea>
                                        <?= validation_show_error('instructions', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Prescription</button>
                                </form>
                            </div>

                            <!-- Equipment Tab -->
                            <div class="tab-pane fade" id="equipment" role="tabpanel">
                                <form method="post" action="<?= base_url('visits/addDetails') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="visit_id" id="equipmentVisitId" class="set-visit-id">
                                    <input type="hidden" name="type" value="equipment">
                                    <div class="mb-3">
                                        <label class="form-label">Equipment</label>
                                        <select name="equipment_id" class="form-select" required>
                                            <?php foreach ($equipment as $item): ?>
                                                <option value="<?= $item['equipment_id'] ?>"><?= esc($item['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= validation_show_error('equipment_id', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Quantity Used</label>
                                        <input type="number" name="quantity_used" class="form-control" min="1" required>
                                        <?= validation_show_error('quantity_used', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Usage Notes</label>
                                        <textarea name="usage_notes" class="form-control"></textarea>
                                        <?= validation_show_error('usage_notes', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Record Equipment</button>
                                </form>
                            </div>

                            <!-- Supplies Tab -->
                            <div class="tab-pane fade" id="supplies" role="tabpanel">
                                <form method="post" action="<?= base_url('visits/addDetails') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="visit_id" id="supplyVisitId" class="set-visit-id">
                                    <input type="hidden" name="type" value="supply">
                                    <div class="mb-3">
                                        <label class="form-label">Supply</label>
                                        <select name="supply_id" class="form-select" required>
                                            <?php foreach ($supplies as $supply): ?>
                                                <option value="<?= $supply['supply_id'] ?>"><?= esc($supply['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= validation_show_error('supply_id', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Quantity Used</label>
                                        <input type="number" name="quantity_used" class="form-control" required>
                                        <?= validation_show_error('quantity_used', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Usage Type</label>
                                        <select name="usage_type" class="form-select" required>
                                            <option value="standard">Standard</option>
                                            <option value="estimated">Estimated</option>
                                            <option value="bulk">Bulk</option>
                                        </select>
                                        <?= validation_show_error('usage_type', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Record Supply</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Visit Details Modal -->
        <div class="modal fade" id="editVisitDetailsModal" tabindex="-1" aria-labelledby="editVisitDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Visit Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs mb-3" id="editVisitDetailTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="editPrescriptions-tab" data-bs-toggle="tab" data-bs-target="#editPrescriptions" type="button" role="tab">Prescriptions</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="editEquipment-tab" data-bs-toggle="tab" data-bs-target="#editEquipment" type="button" role="tab">Equipment Used</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="editSupplies-tab" data-bs-toggle="tab" data-bs-target="#editSupplies" type="button" role="tab">Supplies Used</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Prescriptions -->
                            <div class="tab-pane fade show active" id="editPrescriptions">
                                <form method="post" action="<?= base_url('visits/updateDetails') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="type" value="prescription">
                                    <input type="hidden" name="visit_id" id="editPrescriptionVisitId">
                                    <input type="hidden" name="record_id" id="editPrescriptionId">
                                    <div class="mb-3">
                                        <label>Drug</label>
                                        <select name="drug_id" id="editDrugId" class="form-select" required>
                                            <?php foreach ($drugs as $drug): ?>
                                                <option value="<?= $drug['drug_id'] ?>"><?= esc($drug['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= validation_show_error('drug_id', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Dosage</label>
                                        <input type="text" name="dosage" id="editDosage" class="form-control" required>
                                        <?= validation_show_error('dosage', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Duration</label>
                                        <input type="text" name="duration" id="editDuration" class="form-control">
                                        <?= validation_show_error('duration', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Quantity</label>
                                        <input type="number" name="quantity" id="editQuantity" class="form-control" required>
                                        <?= validation_show_error('quantity', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Instructions</label>
                                        <textarea name="instructions" id="editInstructions" class="form-control"></textarea>
                                        <?= validation_show_error('instructions', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <button type="submit" class="btn btn-success">Update Prescription</button>
                                </form>
                            </div>

                            <!-- Equipment -->
                            <div class="tab-pane fade" id="editEquipment">
                                <form method="post" action="<?= base_url('visits/updateDetails') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="type" value="equipment">
                                    <input type="hidden" name="visit_id" id="editEquipmentVisitId">
                                    <input type="hidden" name="record_id" id="editEquipmentId">
                                    <div class="mb-3">
                                        <label>Equipment</label>
                                        <select name="equipment_id" id="editEquipmentSelect" class="form-select" required>
                                            <?php foreach ($equipment as $item): ?>
                                                <option value="<?= $item['equipment_id'] ?>"><?= esc($item['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= validation_show_error('equipment_id', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Quantity Used</label>
                                        <input type="number" name="quantity_used" id="editEquipmentQty" class="form-control" required>
                                        <?= validation_show_error('quantity_used', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Usage Notes</label>
                                        <textarea name="usage_notes" id="editUsageNotes" class="form-control"></textarea>
                                        <?= validation_show_error('usage_notes', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <button type="submit" class="btn btn-success">Update Equipment</button>
                                </form>
                            </div>

                            <!-- Supplies -->
                            <div class="tab-pane fade" id="editSupplies">
                                <form method="post" action="<?= base_url('visits/updateDetails') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="type" value="supply">
                                    <input type="hidden" name="visit_id" id="editSupplyVisitId">
                                    <input type="hidden" name="record_id" id="editSupplyId">
                                    <div class="mb-3">
                                        <label>Supply</label>
                                        <select name="supply_id" id="editSupplySelect" class="form-select" required>
                                            <?php foreach ($supplies as $supply): ?>
                                                <option value="<?= $supply['supply_id'] ?>"><?= esc($supply['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= validation_show_error('supply_id', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Quantity Used</label>
                                        <input type="number" name="quantity_used" id="editSupplyQty" class="form-control" required>
                                        <?= validation_show_error('quantity_used', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label>Usage Type</label>
                                        <select name="usage_type" id="editUsageType" class="form-select" required>
                                            <option value="standard">Standard</option>
                                            <option value="estimated">Estimated</option>
                                            <option value="bulk">Bulk</option>
                                        </select>
                                        <?= validation_show_error('usage_type', '<div class="text-danger small mt-1">', '</div>') ?>
                                    </div>
                                    <button type="submit" class="btn btn-success">Update Supply</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Visit Details Modal -->
        <div class="modal fade" id="editVisitDetailsModal" tabindex="-1" aria-labelledby="editVisitDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Visit Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs mb-3" id="editVisitDetailTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active"
                                    id="editPrescriptions-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#editPrescriptions"
                                    type="button"
                                    role="tab"
                                    aria-controls="editPrescriptions"
                                    aria-selected="true">
                                    Prescriptions
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"
                                    id="editEquipment-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#editEquipment"
                                    type="button"
                                    role="tab"
                                    aria-controls="editEquipment"
                                    aria-selected="false">
                                    Equipment Used
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"
                                    id="editSupplies-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#editSupplies"
                                    type="button"
                                    role="tab"
                                    aria-controls="editSupplies"
                                    aria-selected="false">
                                    Supplies Used
                                </button>
                            </li>
                        </ul>


                        <div class="tab-content">
                            <!-- Prescriptions -->
                            <div class="tab-pane fade show active" id="editPrescriptions">
                                <form method="post" action="<?= base_url('visits/updateDetails') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="type" value="prescription">
                                    <input type="hidden" name="visit_id" id="editPrescriptionVisitId">
                                    <input type="hidden" name="record_id" id="editPrescriptionId">
                                    <div class="mb-3">
                                        <label>Drug</label>
                                        <select name="drug_id" id="editDrugId" class="form-select" required>
                                            <?php foreach ($drugs as $drug): ?>
                                                <option value="<?= $drug['drug_id'] ?>"><?= esc($drug['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Dosage</label>
                                        <input type="text" name="dosage" id="editDosage" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Duration</label>
                                        <input type="text" name="duration" id="editDuration" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Quantity</label>
                                        <input type="number" name="quantity" id="editQuantity" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Instructions</label>
                                        <textarea name="instructions" id="editInstructions" class="form-control"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Update Prescription</button>
                                </form>
                            </div>

                            <!-- Equipment -->
                            <div class="tab-pane fade" id="editEquipment">
                                <form method="post" action="<?= base_url('visits/updateDetails') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="type" value="equipment">
                                    <input type="hidden" name="visit_id" id="editEquipmentVisitId">
                                    <input type="hidden" name="record_id" id="editEquipmentId">
                                    <div class="mb-3">
                                        <label>Equipment</label>
                                        <select name="equipment_id" id="editEquipmentSelect" class="form-select" required>
                                            <?php foreach ($equipment as $item): ?>
                                                <option value="<?= $item['equipment_id'] ?>"><?= esc($item['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Quantity Used</label>
                                        <input type="number" name="quantity_used" id="editEquipmentQty" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Usage Notes</label>
                                        <textarea name="usage_notes" id="editUsageNotes" class="form-control"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Update Equipment</button>
                                </form>
                            </div>

                            <!-- Supplies -->
                            <div class="tab-pane fade" id="editSupplies">
                                <form method="post" action="<?= base_url('visits/updateDetails') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="type" value="supply">
                                    <input type="hidden" name="visit_id" id="editSupplyVisitId">
                                    <input type="hidden" name="record_id" id="editSupplyId">
                                    <div class="mb-3">
                                        <label>Supply</label>
                                        <select name="supply_id" id="editSupplySelect" class="form-select" required>
                                            <?php foreach ($supplies as $supply): ?>
                                                <option value="<?= $supply['supply_id'] ?>"><?= esc($supply['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Quantity Used</label>
                                        <input type="number" name="quantity_used" id="editSupplyQty" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Usage Type</label>
                                        <select name="usage_type" id="editUsageType" class="form-select" required>
                                            <option value="standard">Standard</option>
                                            <option value="estimated">Estimated</option>
                                            <option value="bulk">Bulk</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success">Update Supply</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const addVisitStatusSelect = document.querySelector('#addVisitStatus');
        const addReferralNotesGroup = document.querySelector('#addReferralNotesGroup');


        // Set Visit ID for Related Modals (Prescription, Equipment, Supply)
        document.querySelectorAll('.link-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const visitId = btn.dataset.id;
                document.getElementById('prescriptionVisitId').value = visitId;
                document.getElementById('equipmentVisitId').value = visitId;
                document.getElementById('supplyVisitId').value = visitId;
            });
        });

        // View Visit Modal
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('viewPatient').innerText = btn.dataset.patient;
                document.getElementById('viewDoctor').innerText = btn.dataset.doctor;
                document.getElementById('viewDate').innerText = btn.dataset.date;
                document.getElementById('viewWeight').innerText = btn.dataset.weight + " kg";
                document.getElementById('viewObservations').innerText = btn.dataset.observations;
                document.getElementById('viewDiagnosis').innerText = btn.dataset.diagnosis;
                document.getElementById('viewTreatment').innerText = btn.dataset.treatment;
                document.getElementById('viewPatientCondition').innerText = btn.dataset.patient_condition;
                document.getElementById('viewAdmission').innerText = btn.dataset.admission;
                document.getElementById('viewDischarge').innerText = btn.dataset.discharge;
                document.getElementById('viewReferral').innerText = btn.dataset.referral;
                document.getElementById('viewBP').innerText = btn.dataset.blood_pressure;
                document.getElementById('viewHR').innerText = btn.dataset.heart_rate + " bpm";
                document.getElementById('viewTemp').innerText = btn.dataset.temperature + " °C";


                // Automatically update visit_id for any dependent modals
                const visitId = btn.dataset.id;
                document.querySelectorAll('.set-visit-id').forEach(input => {
                    input.value = visitId;
                });
            });
        });

        // Edit Visit Modal
        document.querySelectorAll('.edit-visit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('editVisitId').value = btn.dataset.id;
                document.getElementById('editPatientId').value = btn.dataset.patient_id;
                document.getElementById('editDoctorId').value = btn.dataset.doctor_id;
                document.getElementById('editVisitDate').value = btn.dataset.date;
                document.getElementById('editWeight').value = btn.dataset.weight;
                document.getElementById('editObservations').value = btn.dataset.observations;
                document.getElementById('editDiagnosis').value = btn.dataset.diagnosis;
                document.getElementById('editTreatment').value = btn.dataset.treatment;
                document.getElementById('editPatientCondition').value = btn.dataset.patient_condition;
                document.getElementById('editAdmissionTime').value = btn.dataset.admission;
                document.getElementById('editDischargeTime').value = btn.dataset.discharge;
                document.getElementById('editReferralNotes').value = btn.dataset.referral;
                document.getElementById('editVisitStatus').value = btn.dataset.status;
                document.getElementById('editBloodPressure').value = btn.dataset.blood_pressure;
                document.getElementById('editHeartRate').value = btn.dataset.heart_rate;
                document.getElementById('editTemperature').value = btn.dataset.temperature;


            });
        });

        // Delete Visit Modal
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('deleteVisitId').value = btn.dataset.id;
            });
        });

        // 🔁 Edit Visit Details (Prescriptions, Equipment, Supplies)
        document.querySelectorAll('.edit-visit-details-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const visitId = btn.dataset.id;

                // Set visit ID for all tabs
                document.getElementById('prescriptionVisitId').value = visitId;
                document.getElementById('equipmentVisitId').value = visitId;
                document.getElementById('supplyVisitId').value = visitId;

                // Fetch existing data
                fetch(`/visits/details/${visitId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.prescription) {
                            document.getElementById('editDrugId').value = data.prescription.drug_id;
                            document.getElementById('editDosage').value = data.prescription.dosage;
                            document.getElementById('editDuration').value = data.prescription.duration;
                            document.getElementById('editQuantity').value = data.prescription.quantity;
                            document.getElementById('editInstructions').value = data.prescription.instructions;
                        }

                        if (data.equipment) {
                            document.getElementById('editEquipmentSelect').value = data.equipment.equipment_id;
                            document.getElementById('editEquipmentQty').value = data.equipment.quantity_used;
                            document.getElementById('editUsageNotes').value = data.equipment.usage_notes;
                        }

                        if (data.supply) {
                            document.getElementById('editSupplySelect').value = data.supply.supply_id;
                            document.getElementById('editSupplyQty').value = data.supply.quantity_used;
                            document.getElementById('editUsageType').value = data.supply.usage_type;
                        }
                    });
            });
        });

        // Auto-show Referral Notes if "Referred" is selected
        const visitStatusSelect = document.getElementById('editVisitStatus');
        const referralNotesGroup = document.getElementById('referralNotesGroup');

        if (visitStatusSelect && referralNotesGroup) {
            function toggleReferralNotes() {
                if (visitStatusSelect.value === 'Referred') {
                    referralNotesGroup.style.display = 'block';
                } else {
                    referralNotesGroup.style.display = 'none';
                }
            }

            // Initial check
            toggleReferralNotes();

            // On change
            visitStatusSelect.addEventListener('change', toggleReferralNotes);
        }


    });
</script>





<?= $this->endSection() ?>