<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Sidebar -->
<?= view('partials/sidenav') ?>
<main class="dashboard-main">
    <?= view('partials/topbar') ?>

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Basic Table</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="index.html" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Doctors Records</li>
            </ul>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Doctor Records -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Doctor Records</h5>
                <!-- Optional: hook up your add modal if you have it -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal">+ New Doctor</button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th style="width:140px">Action</th>
                            </tr>
                        </thead>
                        <tbody id="doctorsTbody">
                            <?php foreach ($doctors as $doctor): ?>
                                <tr data-id="<?= (int)$doctor['doctor_id'] ?>">
                                    <td><?= esc($doctor['first_name']) ?></td>
                                    <td><?= esc($doctor['last_name']) ?></td>
                                    <td><?= esc($doctor['email']) ?></td>
                                    <td><?= esc($doctor['phone_number']) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button"
                                                class="w-32-px h-32-px btn btn-success rounded-circle d-inline-flex align-items-center justify-content-center js-edit"
                                                title="Edit Doctor"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editDoctorModal"
                                                data-id="<?= (int)$doctor['doctor_id'] ?>"
                                                data-first_name="<?= esc($doctor['first_name']) ?>"
                                                data-last_name="<?= esc($doctor['last_name']) ?>"
                                                data-email="<?= esc($doctor['email']) ?>"
                                                data-phone="<?= esc($doctor['phone_number']) ?>">
                                                <iconify-icon icon="mdi:pencil-outline" class="text-white text-lg"></iconify-icon>
                                            </button>

                                            <button type="button"
                                                class="w-32-px h-32-px btn btn-danger rounded-circle d-inline-flex align-items-center justify-content-center js-delete"
                                                title="Delete Doctor"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteDoctorModal"
                                                data-id="<?= (int)$doctor['doctor_id'] ?>">
                                                <iconify-icon icon="mingcute:delete-2-line" class="text-white text-lg"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php
                // ---------- Pager (Doctors) ----------
                $rq        = \Config\Services::request();
                $group     = 'doctors';
                $current   = (int)($pager->getCurrentPage($group) ?? 1);
                $pageCount = (int)($pager->getPageCount($group) ?? 1);
                $perPage   = (int)($pager->getPerPage($group) ?? ($rq->getGet('per_page') ?? 10));
                $hasPrev   = $current > 1;
                $hasNext   = $current < max(1, $pageCount);

                // preserve non-paging filters
                $keep = $rq->getGet();
                unset($keep['page'], $keep['page_' . $group]);
                $keep['per_page'] = $perPage;
                $qs = http_build_query($keep);

                $withQuery = static function (?string $uri, string $qs) {
                    if (!$uri) return '#';
                    return str_contains($uri, '?') ? "$uri&$qs" : "$uri?$qs";
                };

                $prevUri = $hasPrev ? $withQuery($pager->getPreviousPageURI($group), $qs) : '#';
                $nextUri = $hasNext ? $withQuery($pager->getNextPageURI($group), $qs)     : '#';

                $pad    = strlen((string)max(1, $pageCount));
                $fmtNum = static fn($n) => str_pad((string)$n, $pad, '0', STR_PAD_LEFT);
                ?>

                <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <!-- counter -->
                    <div class="page-chip">
                        <span class="current"><?= $fmtNum($current) ?></span>
                        <span class="slash">/</span>
                        <span class="total"><?= $fmtNum($pageCount) ?></span>
                    </div>

                    <!-- prev/next -->
                    <nav class="page-nav d-flex align-items-center gap-2" aria-label="Doctors pagination">
                        <a class="btn btn-light btn-icon<?= $hasPrev ? '' : ' disabled' ?>"
                            href="<?= esc($prevUri) ?>"
                            <?= $hasPrev ? 'rel="prev"' : 'aria-disabled="true" tabindex="-1"' ?>
                            aria-label="Previous page"><span aria-hidden="true">&lsaquo;</span></a>

                        <a class="btn btn-light btn-icon<?= $hasNext ? '' : ' disabled' ?>"
                            href="<?= esc($nextUri) ?>"
                            <?= $hasNext ? 'rel="next"' : 'aria-disabled="true" tabindex="-1"' ?>
                            aria-label="Next page"><span aria-hidden="true">&rsaquo;</span></a>
                    </nav>

                    <!-- rows per page -->
                    <form method="get" class="d-flex align-items-center gap-2 ms-auto">
                        <?php
                        foreach ($rq->getGet() as $k => $v) {
                            if (in_array($k, ['per_page', 'page', 'page_' . $group], true)) continue;
                            $val = is_array($v) ? implode(',', $v) : $v;
                            echo '<input type="hidden" name="' . esc($k) . '" value="' . esc($val) . '">';
                        }
                        ?>
                        <label class="text-muted small">Rows</label>
                        <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                            <?php foreach ([10, 25, 50, 100] as $opt): ?>
                                <option value="<?= $opt ?>" <?= ($perPage === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>


        <!-- Add Doctor Modal -->
        <div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDoctorModalLabel">Add New Doctor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addDoctorForm" method="post" action="<?= base_url('doctors/store') ?>">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="first_name" placeholder="e.g. John" required>
                                </div>
                                <div class="col-12">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="last_name" placeholder="e.g. Doe" required>
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="e.g. john.doe@example.com" required>
                                </div>
                                <div class="col-12">
                                    <label for="phoneNumber" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phoneNumber" name="phone_number" placeholder="e.g. +256 700 000000" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Doctor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Doctor Modal -->
        <div class="modal fade" id="editDoctorModal" tabindex="-1" aria-labelledby="editDoctorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="editDoctorForm" method="post" action="<?= base_url('doctors/update') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDoctorModalLabel">Edit Doctor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" name="id" id="editDoctorId">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="editFirstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="editFirstName" name="first_name" required>
                                </div>
                                <div class="col-12">
                                    <label for="editLastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="editLastName" name="last_name" required>
                                </div>
                                <div class="col-12">
                                    <label for="editEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="editEmail" name="email" required>
                                </div>
                                <div class="col-12">
                                    <label for="editPhone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="editPhone" name="phone_number" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Doctor</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Doctor Modal -->
        <div class="modal fade" id="deleteDoctorModal" tabindex="-1" aria-labelledby="deleteDoctorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('doctors/delete') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteDoctorModalLabel">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this doctor record?
                            <input type="hidden" name="id" id="deleteDoctorId">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('editDoctorId').value = this.dataset.id;
                document.getElementById('editFirstName').value = this.dataset.first_name;
                document.getElementById('editLastName').value = this.dataset.last_name;
                document.getElementById('editEmail').value = this.dataset.email;
                document.getElementById('editPhone').value = this.dataset.phone;
            });
        });
    });
</script>

<script>
    const deleteModal = document.getElementById('deleteDoctorModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const doctorId = button.getAttribute('data-id');
        deleteModal.querySelector('#deleteDoctorId').value = doctorId;
    });
</script>


<?= $this->endSection() ?>