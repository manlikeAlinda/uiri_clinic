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
                            <th>Diagnosis</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visits as $visit): ?>
                            <tr>
                                <td><?= esc($visit['patient_name']) ?></td>
                                <td><?= esc($visit['doctor_name']) ?></td>
                                <td><?= esc($visit['visit_date']) ?></td>
                                <td><?= esc($visit['weight']) ?></td>
                                <td>
                                    <small>BP: <?= esc($visit['blood_pressure']) ?: '-' ?></small><br>
                                    <small>Pulse: <?= esc($visit['pulse']) ?: '-' ?> bpm</small><br>
                                    <small>Temp: <?= esc($visit['temperature']) ?: '-' ?> °C</small><br>
                                    <small>SpO₂: <?= esc($visit['sp02']) ?: '-' ?> %</small><br>
                                    <small>Resp: <?= esc($visit['respiration_rate']) ?: '-' ?> bpm</small>
                                </td>
                                <td><?= esc($visit['diagnosis']) ?></td>
                                <td class="d-flex gap-2">
                                    <!-- View -->
                                    <button class="btn btn-info btn-sm view-btn"
                                        data-bs-toggle="modal" data-bs-target="#viewVisitModal"
                                        data-id="<?= $visit['visit_id'] ?>"
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
                                        <iconify-icon icon="mdi:eye-outline" width="20" height="20" class="text-white"></iconify-icon>
                                    </button>

                                    <!-- Edit -->
                                    <button class="btn btn-success btn-sm edit-visit-btn"
                                        data-bs-toggle="modal" data-bs-target="#editVisitModal"
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
                                        <iconify-icon icon="mdi:pencil-outline" width="20" height="20" class="text-white"></iconify-icon>
                                    </button>

                                    <!-- Delete -->
                                    <button
                                        class="btn btn-danger btn-sm delete-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteVisitModal"
                                        data-id="<?= esc($visit['visit_id']) ?>"
                                        data-patient="<?= esc($visit['patient_name']) ?>">
                                        <iconify-icon icon="mingcute:delete-2-line" width="20" height="20" class="text-white"></iconify-icon>
                                    </button>


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
                            <div class="container-fluid">
                                <!-- Group 1: Basic Info -->
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

                                <!-- Group 2: Vitals -->
                                <div class="row gx-3 gy-3">
                                    <div class="col-md-6">
                                        <label for="blood_pressure" class="form-label">Blood Pressure</label>
                                        <input id="blood_pressure" type="text" name="blood_pressure" class="form-control" placeholder="120/80 mmHg">
                                        <?= validation_show_error('blood_pressure', '<div class="text-danger small">', '</div>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="weight" class="form-label">Weight (kg)</label>
                                        <input id="weight" type="number" step="0.1" name="weight" class="form-control">
                                        <?= validation_show_error('weight', '<div class="text-danger small">', '</div>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pulse" class="form-label">Pulse (bpm)</label>
                                        <input id="pulse" type="number" name="pulse" class="form-control">
                                        <?= validation_show_error('pulse', '<div class="text-danger small">', '</div>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="temperature" class="form-label">Temperature (°C)</label>
                                        <input id="temperature" type="number" step="0.1" name="temperature" class="form-control">
                                        <?= validation_show_error('temperature', '<div class="text-danger small">', '</div>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="sp02" class="form-label">SpO₂ (%)</label>
                                        <input id="sp02" type="number" step="0.1" name="sp02" class="form-control">
                                        <?= validation_show_error('sp02', '<div class="text-danger small">', '</div>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="respiration_rate" class="form-label">Respiration Rate (bpm)</label>
                                        <input id="respiration_rate" type="number" name="respiration_rate" class="form-control">
                                        <?= validation_show_error('respiration_rate', '<div class="text-danger small">', '</div>') ?>
                                    </div>
                                </div>

                                <!-- Group 3: Assessment & Diagnosis -->
                                <div class="row gx-3 gy-3">
                                    <div class="col-md-6">
                                        <label for="patient_complaints" class="form-label">Patient Complaints</label>
                                        <textarea id="patient_complaints" name="patient_complaints" class="form-control" rows="2" required></textarea>
                                        <?= validation_show_error('patient_complaints', '<div class="text-danger small">', '</div>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="examination_notes" class="form-label">Examination Notes</label>
                                        <textarea id="examination_notes" name="examination_notes" class="form-control" rows="2"></textarea>
                                        <?= validation_show_error('examination_notes', '<div class="text-danger small">', '</div>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="investigations" class="form-label">Investigations (Lab Results)</label>
                                        <textarea id="investigations" name="investigations" class="form-control" rows="2"></textarea>
                                        <?= validation_show_error('investigations', '<div class="text-danger small">', '</div>') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="diagnosis" class="form-label">Diagnosis</label>
                                        <textarea id="diagnosis" name="diagnosis" class="form-control" rows="2" required></textarea>
                                        <?= validation_show_error('diagnosis', '<div class="text-danger small">', '</div>') ?>
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


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ---- ADD VISIT LOGIC ----
                const addCategory = document.getElementById('addVisitCategory');
                const addAdmCont = document.getElementById('addAdmissionContainer');

                function toggleAddFields() {
                    if (!addCategory || !addAdmCont) return;

                    if (addCategory.value === 'in-patient') {
                        addAdmCont.style.display = 'block';
                    } else {
                        addAdmCont.style.display = 'none';
                    }
                }

                if (addCategory) {
                    addCategory.addEventListener('change', toggleAddFields);
                    toggleAddFields();
                }

                // ✅ DELETE BUTTON HANDLER (reliable)
                const deleteModal = document.getElementById('deleteVisitModal');
                const deleteVisitIdInput = document.getElementById('deleteVisitId');
                const deletePatientNameSpan = document.getElementById('deletePatientName');

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const visitId = btn.getAttribute('data-id');
                        const patientName = btn.getAttribute('data-patient');

                        if (deleteVisitIdInput) deleteVisitIdInput.value = visitId;
                        if (deletePatientNameSpan) deletePatientNameSpan.textContent = patientName;

                        console.log(`Preparing to delete visit ID: ${visitId} for ${patientName}`);
                    });
                });
            });
        </script>



    </div>
</main>

<?= $this->endSection() ?>