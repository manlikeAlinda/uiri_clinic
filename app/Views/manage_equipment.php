<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Sidebar -->
<?= view('partials/sidenav') ?>
<main class="dashboard-main">
    <?= view('partials/topbar') ?>

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Manage Equipment</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="<?= base_url('dashboard') ?>" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Equipment</li>
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
                <h5 class="card-title mb-0">Equipment List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">+ New Equipment</button>
            </div>
            <div class="card-body">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Batch No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($equipment as $item): ?>
                            <tr>
                                <td><?= esc($item['name']) ?></td>
                                <td><?= esc($item['quantity_in_stock']) ?></td>
                                <td><?= esc($item['status']) ?></td>
                                <td><?= esc($item['batch_no']) ?></td>
                                <td>
                                    <a href="javascript:void(0)" 
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center edit-equipment-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editEquipmentModal"
                                        data-id="<?= $item['equipment_id'] ?>"
                                        data-name="<?= esc($item['name']) ?>"
                                        data-quantity="<?= esc($item['quantity_in_stock']) ?>"
                                        data-status="<?= esc($item['status']) ?>"
                                        data-batch="<?= esc($item['batch_no']) ?>">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </a>

                                    <button 
                                        type="button" 
                                        class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center delete-equipment-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteEquipmentModal"
                                        data-id="<?= $item['equipment_id'] ?>">
                                        <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="mt-3 d-flex justify-content-center">
    <?= $pager->links() ?>
</div>

            </div>
        </div>

        <!-- Add Equipment Modal -->
        <div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('equipment/store') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addEquipmentModalLabel">Add New Equipment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Equipment Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Quantity In Stock</label>
                                    <input type="number" class="form-control" name="quantity_in_stock" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="Available">Available</option>
                                        <option value="Unavailable">Unavailable</option>
                                        <option value="Under Repair">Under Repair</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Batch No</label>
                                    <input type="text" class="form-control" name="batch_no" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Equipment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Equipment Modal -->
        <div class="modal fade" id="editEquipmentModal" tabindex="-1" aria-labelledby="editEquipmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('equipment/update') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editEquipmentModalLabel">Edit Equipment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="editEquipmentId">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Equipment Name</label>
                                    <input type="text" class="form-control" name="name" id="editEquipmentName" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Quantity In Stock</label>
                                    <input type="number" class="form-control" name="quantity_in_stock" id="editEquipmentQuantity" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" id="editEquipmentStatus" required>
                                        <option value="Available">Available</option>
                                        <option value="Unavailable">Unavailable</option>
                                        <option value="Under Repair">Under Repair</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Batch No</label>
                                    <input type="text" class="form-control" name="batch_no" id="editEquipmentBatch" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Equipment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Equipment Modal -->
        <div class="modal fade" id="deleteEquipmentModal" tabindex="-1" aria-labelledby="deleteEquipmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('equipment/delete') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteEquipmentModalLabel">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this equipment?
                            <input type="hidden" name="id" id="deleteEquipmentId">
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
                <p class="mb-0">© 2024 WowDash. All Rights Reserved.</p>
            </div>
            <div class="col-auto">
                <p class="mb-0">Made by <span class="text-primary-600">wowtheme7</span></p>
            </div>
        </div>
    </footer>
</main>

<!-- JavaScript for handling modal population -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-equipment-btn');
        const deleteButtons = document.querySelectorAll('.delete-equipment-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('editEquipmentId').value = this.dataset.id;
                document.getElementById('editEquipmentName').value = this.dataset.name;
                document.getElementById('editEquipmentQuantity').value = this.dataset.quantity;
                document.getElementById('editEquipmentStatus').value = this.dataset.status;
                document.getElementById('editEquipmentBatch').value = this.dataset.batch;
            });
        });

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('deleteEquipmentId').value = this.dataset.id;
            });
        });
    });
</script>

<?= $this->endSection() ?>
