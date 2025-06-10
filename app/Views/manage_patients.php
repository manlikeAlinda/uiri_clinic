<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Sidebar -->
<?= view('partials/sidenav') ?>

<main class="dashboard-main">
    <?= view('partials/topbar') ?>

    <div class="dashboard-main-body">

        <!-- Breadcrumb -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Manage Patients</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="<?= base_url('dashboard') ?>" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Manage Patients</li>
            </ul>
        </div>

        <!-- Success Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Patients Table -->
        <div class="card basic-data-table">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Patients List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">+ New Patient</button>
            </div>

            <div class="card-body">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Date of Birth</th>
                            <th>Gender</th>
                            <th>Contact Info</th>
                            <th>Next of Kin Contact</th>
                            <th>Next of Kin Relationship</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $patient): ?>
                            <tr>
                                <td><?= esc($patient['first_name']) ?></td>
                                <td><?= esc($patient['last_name']) ?></td>
                                <td><?= esc($patient['date_of_birth']) ?></td>
                                <td><?= esc($patient['gender']) ?></td>
                                <td><?= esc($patient['contact_info']) ?></td>
                                <td><?= esc($patient['next_of_kin_contact']) ?></td>
                                <td><?= esc($patient['next_of_kin_relationship']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button"
                                            class="w-32-px h-32-px btn btn-info rounded-circle d-inline-flex align-items-center justify-content-center view-btn"
                                            title="View Patient"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewPatientModal"
                                            data-id="<?= $patient['patient_id'] ?>"
                                            data-first_name="<?= esc($patient['first_name']) ?>"
                                            data-last_name="<?= esc($patient['last_name']) ?>"
                                            data-dob="<?= esc($patient['date_of_birth']) ?>"
                                            data-gender="<?= esc($patient['gender']) ?>"
                                            data-contact_info="<?= esc($patient['contact_info']) ?>"
                                            data-next_of_kin_contact="<?= esc($patient['next_of_kin_contact']) ?>"
                                            data-next_of_kin_relationship="<?= esc($patient['next_of_kin_relationship']) ?>">
                                            <iconify-icon icon="mdi:eye-outline" class="text-white text-lg"></iconify-icon>
                                        </button>

                                        <button type="button"
                                            class="w-32-px h-32-px btn btn-success rounded-circle d-inline-flex align-items-center justify-content-center edit-btn"
                                            title="Edit Patient"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editPatientModal"
                                            data-id="<?= $patient['patient_id'] ?>"
                                            data-first_name="<?= esc($patient['first_name']) ?>"
                                            data-last_name="<?= esc($patient['last_name']) ?>"
                                            data-dob="<?= esc($patient['date_of_birth']) ?>"
                                            data-gender="<?= esc($patient['gender']) ?>"
                                            data-contact_info="<?= esc($patient['contact_info']) ?>"
                                            data-next_of_kin_contact="<?= esc($patient['next_of_kin_contact']) ?>"
                                            data-next_of_kin_relationship="<?= esc($patient['next_of_kin_relationship']) ?>">
                                            <iconify-icon icon="mdi:pencil-outline" class="text-white text-lg"></iconify-icon>
                                        </button>

                                        <button type="button"
                                            class="w-32-px h-32-px btn btn-danger rounded-circle d-inline-flex align-items-center justify-content-center delete-btn"
                                            title="Delete Patient"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deletePatientModal"
                                            data-id="<?= $patient['patient_id'] ?>">
                                            <iconify-icon icon="mingcute:delete-2-line" class="text-white text-lg"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Patient Modal -->
        <!-- Add Patient Modal -->
        <div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('patients/store') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Patient</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Gender</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Contact Info</label>
                                    <input type="text" name="contact_info" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>Next of Kin Contact</label>
                                    <input type="text" name="next_of_kin_contact" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label>Next of Kin Relationship</label>
                                    <select name="next_of_kin_relationship" class="form-select" required>
                                        <option value="">Select</option>
                                        <option value="Spouse">Spouse</option>
                                        <option value="Child">Child</option>
                                        <option value="Parent">Parent</option>
                                        <option value="Sibling">Sibling</option>
                                        <option value="Guardian">Guardian</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Medical History</label>
                                    <textarea name="medical_history" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save Patient</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Edit Patient Modal -->
        <div class="modal fade" id="editPatientModal" tabindex="-1" aria-labelledby="editPatientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('patients/update') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Patient</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="patient_id" id="editPatientId">
                            <div class="row g-3">
                                <div class="col-12"><label>First Name</label><input type="text" id="editFirstName" name="first_name" class="form-control" required></div>
                                <div class="col-12"><label>Last Name</label><input type="text" id="editLastName" name="last_name" class="form-control" required></div>
                                <div class="col-12"><label>Date of Birth</label><input type="date" id="editDob" name="date_of_birth" class="form-control" required></div>
                                <div class="col-12"><label>Gender</label>
                                    <select id="editGender" name="gender" class="form-select" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-12"><label>Contact Info</label><input type="text" id="editContact" name="contact_info" class="form-control"></div>
                                <div class="col-12"><label>Medical History</label><textarea id="editMedicalHistory" name="medical_history" class="form-control" rows="2"></textarea></div>
                                <div class="col-12"><label>Next of Kin Contact</label><input type="text" id="editNextOfKinContact" name="next_of_kin_contact" class="form-control"></div>
                                <div class="col-12"><label>Next of Kin Relationship</label>
                                    <select id="editNextOfKinRelationship" name="next_of_kin_relationship" class="form-select" required>
                                        <option value="Spouse">Spouse</option>
                                        <option value="Child">Child</option>
                                        <option value="Parent">Parent</option>
                                        <option value="Sibling">ibling</option>
                                        <option value="Guardian">Guardian</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update Patient</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Patient Modal -->
        <div class="modal fade" id="deletePatientModal" tabindex="-1" aria-labelledby="deletePatientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('patients/delete') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Patient</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this patient?
                            <input type="hidden" name="patient_id" id="deletePatientId">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- View Patient Modal -->
        <div class="modal fade" id="viewPatientModal" tabindex="-1" aria-labelledby="viewPatientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Patient Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>First Name:</strong> <span id="viewFirstName"></span></p>
                        <p><strong>Last Name:</strong> <span id="viewLastName"></span></p>
                        <p><strong>Date of Birth:</strong> <span id="viewDob"></span></p>
                        <p><strong>Gender:</strong> <span id="viewGender"></span></p>
                        <p><strong>Contact Info:</strong> <span id="viewContact"></span></p>
                        <p><strong>Medical History:</strong> <span id="viewMedicalHistory"></span></p>
                        <p><strong>Next of Kin Contact:</strong> <span id="viewNextOfKinContact"></span></p>
                        <p><strong>Next of Kin Relationship:</strong> <span id="viewNextOfKinRelationship"></span></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-btn');
        const viewButtons = document.querySelectorAll('.view-btn');
        const deleteButtons = document.querySelectorAll('.delete-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('editPatientId').value = this.dataset.id;
                document.getElementById('editFirstName').value = this.dataset.first_name;
                document.getElementById('editLastName').value = this.dataset.last_name;
                document.getElementById('editDob').value = this.dataset.dob;
                document.getElementById('editGender').value = this.dataset.gender;
                document.getElementById('editContact').value = this.dataset.contact_info;
                document.getElementById('editMedicalHistory').value = this.dataset.medical_history;
                document.getElementById('editNextOfKinContact').value = this.dataset.next_of_kin_contact;
                document.getElementById('editNextOfKinRelationship').value = this.dataset.next_of_kin_relationship;
            });
        });

        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('viewFirstName').innerText = this.dataset.first_name;
                document.getElementById('viewLastName').innerText = this.dataset.last_name;
                document.getElementById('viewDob').innerText = this.dataset.dob;
                document.getElementById('viewGender').innerText = this.dataset.gender;
                document.getElementById('viewContact').innerText = this.dataset.contact_info;
                document.getElementById('viewMedicalHistory').innerText = this.dataset.medical_history;
                document.getElementById('viewNextOfKinContact').innerText = this.dataset.next_of_kin_contact;
                document.getElementById('viewNextOfKinRelationship').innerText = this.dataset.next_of_kin_relationship;
            });
        });

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('deletePatientId').value = this.dataset.id;
            });
        });
    });
</script>

<?= $this->endSection() ?>