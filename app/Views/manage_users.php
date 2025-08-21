<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Sidebar + Topbar -->
<?= view('partials/sidenav') ?>
<main class="dashboard-main">
    <?= view('partials/topbar') ?>

    <div class="dashboard-main-body">

        <!-- Breadcrumb -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <?php if (!empty($breadcrumb)): ?>
                <h6 class="fw-semibold mb-0"><?= end($breadcrumb)['title'] ?></h6>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <?php foreach ($breadcrumb as $item): ?>
                            <?php if ($item['active']): ?>
                                / <li class="breadcrumb-item active" aria-current="page"><?= esc($item['title']) ?></li>
                            <?php else: ?>
                                <li class="breadcrumb-item"><a href="<?= esc($item['url']) ?>"><?= esc($item['title']) ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </nav>
            <?php endif; ?>
        </div>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <!-- USERS TABLE -->
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Users</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    + New User
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Registered</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= esc($u['username']) ?></td>
                                <td><?= esc(ucfirst($u['role'])) ?></td>
                                <td><?= date('Y‑m‑d', strtotime($u['created_at'])) ?></td>
                                <td class="text-center">
                                    <button
                                        class="btn btn-sm btn-outline-primary me-1 edit-user-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal"
                                        data-id="<?= $u['user_id'] ?>"
                                        data-username="<?= esc($u['username']) ?>"
                                        data-role="<?= esc($u['role']) ?>">
                                        <iconify-icon icon="mdi:pencil-outline"></iconify-icon>
                                    </button>
                                    <button
                                        class="btn btn-sm btn-outline-danger delete-user-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteUserModal"
                                        data-id="<?= $u['user_id'] ?>"
                                        data-name="<?= esc($u['username']) ?>">
                                        <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <?= $pager->links() ?>
            </div>
        </div>

    </div>
</main>

<!-- ADD USER MODAL -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('users/store') ?>" method="post" class="needs-validation" novalidate>
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role *</label>
                        <select name="role" id="addUserRole" class="form-select" required>
                            <option value="" disabled selected>Select Role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= esc($role) ?>"><?= esc(ucfirst($role)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <!-- Hidden unless role === 'Doctor' -->
                    <div id="doctorFields" class="border rounded p-3 d-none">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="doc_first_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="doc_last_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" name="doc_email" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="doc_phone" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- EDIT USER MODAL -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editUserForm" method="post" class="needs-validation" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="user_id" id="editUserId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" id="editUsername" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role *</label>
                        <select name="role" id="editRole" class="form-select" required>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= esc($r) ?>"><?= esc(ucfirst($r)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- DELETE USER MODAL -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('users/delete') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="user_id" id="deleteUserId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="deleteUserName"></strong>?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ---------- Helpers ----------
        const qs = (sel, root = document) => root.querySelector(sel);
        const qsa = (sel, root = document) => Array.from(root.querySelectorAll(sel));

        // ---------- Edit User (event delegation for dynamic rows) ----------
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.edit-user-btn');
            if (!btn) return;
            const id = btn.dataset.id;
            qs('#editUserId').value = id;
            qs('#editUsername').value = btn.dataset.username || '';
            qs('#editRole').value = btn.dataset.role || '';
            qs('#editUserForm').action = `/users/update/${id}`;
        });

        // ---------- Delete User ----------
        const deleteModal = qs('#deleteUserModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', (e) => {
                const btn = e.relatedTarget;
                if (!btn) return;
                qs('#deleteUserId').value = btn.dataset.id || '';
                qs('#deleteUserName').textContent = btn.dataset.name || '';
            });
        }

        // ---------- Add User: toggle Doctor fields + reset ----------
        const addUserModal = qs('#addUserModal');
        const doctorFields = qs('#doctorFields');

        function toggleDoctorFields(selectEl) {
            if (!selectEl || !doctorFields) return;
            const isDoctor = (selectEl.value === 'Doctor');
            doctorFields.classList.toggle('d-none', !isDoctor);

            // mark fields required only when visible
            ['doc_first_name', 'doc_last_name', 'doc_email', 'doc_phone'].forEach((name) => {
                const input = qs(`[name="${name}"]`, addUserModal);
                if (input) input.required = isDoctor;
            });
        }

        if (addUserModal) {
            addUserModal.addEventListener('shown.bs.modal', () => {
                const form = qs('form', addUserModal);
                if (form) {
                    form.reset();
                    form.classList.remove('was-validated');
                }
                const roleSelect = qs('#addUserRole', addUserModal);
                if (roleSelect) {
                    // init state + hook change
                    toggleDoctorFields(roleSelect);
                    roleSelect.addEventListener('change', () => toggleDoctorFields(roleSelect));
                }
            });

            addUserModal.addEventListener('hidden.bs.modal', () => {
                const form = qs('form', addUserModal);
                if (form) {
                    form.reset();
                    form.classList.remove('was-validated');
                }
                if (doctorFields) doctorFields.classList.add('d-none');
            });
        }

        // ---------- Bootstrap validation for all forms ----------
        qsa('form.needs-validation').forEach((form) => {
            form.addEventListener('submit', (ev) => {
                if (!form.checkValidity()) {
                    ev.preventDefault();
                    ev.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    });
</script>


<?= $this->endSection() ?>