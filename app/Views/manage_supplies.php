<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Sidebar -->
<?= view('partials/sidenav') ?>
<main class="dashboard-main">
    <?= view('partials/topbar') ?>

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Manage Supplies</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="<?= base_url('dashboard') ?>" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Manage Supplies</li>
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
                <h5 class="card-title mb-0">Supply Records</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplyModal">+ New Supply</button>
            </div>
            <div class="card-body">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Quantity In Stock</th>
                            <th>Batch No</th>
                            <th>Manufacture Date</th>
                            <th>Expiration Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($supplies as $supply): ?>
                            <tr>
                                <td><?= esc($supply['name']) ?></td>
                                <td><?= esc($supply['quantity_in_stock']) ?></td>
                                <td><?= esc($supply['batch_no']) ?></td>
                                <td><?= esc($supply['manufacture_date']) ?></td>
                                <td><?= esc($supply['expiration_date']) ?></td>
                                <td>
                                    <a href="javascript:void(0)"
                                       class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center edit-btn"
                                       data-bs-toggle="modal"
                                       data-bs-target="#editSupplyModal"
                                       data-id="<?= $supply['supply_id'] ?>"
                                       data-name="<?= esc($supply['name']) ?>"
                                       data-quantity="<?= esc($supply['quantity_in_stock']) ?>"
                                       data-batch="<?= esc($supply['batch_no']) ?>"
                                       data-manufacture="<?= esc($supply['manufacture_date']) ?>"
                                       data-expiration="<?= esc($supply['expiration_date']) ?>">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </a>

                                    <button type="button"
                                            class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteSupplyModal"
                                            data-id="<?= $supply['supply_id'] ?>">
                                        <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Supply Modal -->
        <div class="modal fade" id="addSupplyModal" tabindex="-1" aria-labelledby="addSupplyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('supplies/store') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSupplyModalLabel">Add New Supply</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Quantity In Stock</label>
                                    <input type="number" class="form-control" name="quantity_in_stock" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Batch No</label>
                                    <input type="text" class="form-control" name="batch_no" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Manufacture Date</label>
                                    <input type="date" class="form-control" name="manufacture_date" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Expiration Date</label>
                                    <input type="date" class="form-control" name="expiration_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Supply</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Supply Modal -->
        <div class="modal fade" id="editSupplyModal" tabindex="-1" aria-labelledby="editSupplyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('supplies/update') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSupplyModalLabel">Edit Supply</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="editSupplyId">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" id="editSupplyName" name="name" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Quantity In Stock</label>
                                    <input type="number" class="form-control" id="editSupplyQuantity" name="quantity_in_stock" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Batch No</label>
                                    <input type="text" class="form-control" id="editSupplyBatch" name="batch_no" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Manufacture Date</label>
                                    <input type="date" class="form-control" id="editSupplyManufacture" name="manufacture_date" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Expiration Date</label>
                                    <input type="date" class="form-control" id="editSupplyExpiration" name="expiration_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Supply</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Supply Modal -->
        <div class="modal fade" id="deleteSupplyModal" tabindex="-1" aria-labelledby="deleteSupplyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('supplies/delete') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteSupplyModalLabel">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this supply record?
                            <input type="hidden" name="id" id="deleteSupplyId">
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

<!-- Javascript for filling edit modal dynamically -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('editSupplyId').value = this.dataset.id;
                document.getElementById('editSupplyName').value = this.dataset.name;
                document.getElementById('editSupplyQuantity').value = this.dataset.quantity;
                document.getElementById('editSupplyBatch').value = this.dataset.batch;
                document.getElementById('editSupplyManufacture').value = this.dataset.manufacture;
                document.getElementById('editSupplyExpiration').value = this.dataset.expiration;
            });
        });
    });
</script>

<script>
    const deleteModal = document.getElementById('deleteSupplyModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const supplyId = button.getAttribute('data-id');
        deleteModal.querySelector('#deleteSupplyId').value = supplyId;
    });
</script>

<?= $this->endSection() ?>
