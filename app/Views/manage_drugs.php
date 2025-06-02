<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Sidebar -->
<?= view('partials/sidenav') ?>
<main class="dashboard-main">
    <?= view('partials/topbar') ?>

    <div class="dashboard-main-body">

        <!-- Breadcrumb -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Manage Drugs</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="<?= base_url('dashboard') ?>" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Manage Drugs</li>
            </ul>
        </div>

        <!-- Success Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Drugs Table -->
        <div class="card basic-data-table">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Drugs List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDrugModal">+ New Drug</button>
            </div>
            <div class="card-body">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Dosage</th>
                            <th>Quantity</th>
                            <th>Batch No</th>
                            <th>Manufacture Date</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($drugs as $drug): ?>
                            <tr>
                                <td><?= esc($drug['name']) ?></td>
                                <td><?= esc($drug['dosage']) ?></td>
                                <td><?= esc($drug['quantity_in_stock']) ?></td>
                                <td><?= esc($drug['batch_no']) ?></td>
                                <td><?= esc($drug['manufacture_date']) ?></td>
                                <td><?= esc($drug['expiration_date']) ?></td>
                                <td><?= esc($drug['status']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="javascript:void(0)"
                                            class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center edit-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editDrugModal"
                                            data-id="<?= $drug['drug_id'] ?>"
                                            data-name="<?= esc($drug['name']) ?>"
                                            data-dosage="<?= esc($drug['dosage']) ?>"
                                            data-quantity="<?= esc($drug['quantity_in_stock']) ?>"
                                            data-batch="<?= esc($drug['batch_no']) ?>"
                                            data-manufacture="<?= esc($drug['manufacture_date']) ?>"
                                            data-expiry="<?= esc($drug['expiration_date']) ?>"
                                            data-status="<?= esc($drug['status']) ?>">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </a>

                                        <button type="button"
                                            class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center delete-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteDrugModal"
                                            data-id="<?= $drug['drug_id'] ?>">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Drug Modal -->
        <div class="modal fade" id="addDrugModal" tabindex="-1" aria-labelledby="addDrugModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('drugs/store') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addDrugModalLabel">Add New Drug</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Drug Name" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Dosage</label>
                                    <input type="text" name="dosage" class="form-control" placeholder="e.g. 500mg" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Quantity In Stock</label>
                                    <input type="number" name="quantity_in_stock" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Batch Number</label>
                                    <input type="text" name="batch_no" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Manufacture Date</label>
                                    <input type="date" name="manufacture_date" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Expiration Date</label>
                                    <input type="date" name="expiration_date" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Available">Available</option>
                                        <option value="Out of Stock">Out of Stock</option>
                                        <option value="Expired">Expired</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Drug</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Drug Modal -->
        <div class="modal fade" id="editDrugModal" tabindex="-1" aria-labelledby="editDrugModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('drugs/update') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDrugModalLabel">Edit Drug</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="editDrugId">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input type="text" id="editName" name="name" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Dosage</label>
                                    <input type="text" id="editDosage" name="dosage" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Quantity In Stock</label>
                                    <input type="number" id="editQuantity" name="quantity_in_stock" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Batch Number</label>
                                    <input type="text" id="editBatch" name="batch_no" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Manufacture Date</label>
                                    <input type="date" id="editManufacture" name="manufacture_date" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Expiration Date</label>
                                    <input type="date" id="editExpiry" name="expiration_date" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Status</label>
                                    <select id="editStatus" name="status" class="form-select" required>
                                        <option value="Available">Available</option>
                                        <option value="Out of Stock">Out of Stock</option>
                                        <option value="Expired">Expired</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Drug</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Drug Modal -->
        <div class="modal fade" id="deleteDrugModal" tabindex="-1" aria-labelledby="deleteDrugModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('drugs/delete') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteDrugModalLabel">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this drug record?
                            <input type="hidden" name="id" id="deleteDrugId">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <footer class="d-footer">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <p class="mb-0">© 2025. All Rights Reserved.</p>
            </div>
            <div class="col-auto">
                <p class="mb-0">Made by <span class="text-primary-600">UGANDA INDUSTRIAL RESEARCH INSTITUTE</span></p>
            </div>
        </div>
    </footer>
</main>

<!-- JavaScript to handle modals -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('editDrugId').value = this.dataset.id;
                document.getElementById('editName').value = this.dataset.name;
                document.getElementById('editDosage').value = this.dataset.dosage;
                document.getElementById('editQuantity').value = this.dataset.quantity;
                document.getElementById('editBatch').value = this.dataset.batch;
                document.getElementById('editManufacture').value = this.dataset.manufacture;
                document.getElementById('editExpiry').value = this.dataset.expiry;
                document.getElementById('editStatus').value = this.dataset.status;
            });
        });

        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('deleteDrugId').value = this.dataset.id;
            });
        });
    });
</script>

<?= $this->endSection() ?>