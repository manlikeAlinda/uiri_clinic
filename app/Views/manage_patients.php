<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Sidebar -->
<?= view('partials/sidenav') ?>

<main class="dashboard-main">
    <?= view('partials/topbar') ?>

    <style>
        .page-chip {
            background: var(--bs-light);
            border-radius: 999px;
            padding: .35rem .75rem;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-weight: 600;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .04) inset;
        }

        .page-chip .current {
            color: var(--bs-success);
            font-variant-numeric: tabular-nums;
        }

        .page-chip .total,
        .page-chip .slash {
            color: #6c757d;
            font-variant-numeric: tabular-nums;
        }

        .page-nav .btn-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            padding: 0;
        }

        .page-nav .btn-icon.disabled,
        .page-nav .btn-icon:disabled {
            pointer-events: none;
            opacity: .5;
        }
    </style>

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

        <!-- Success Message (auto-dismiss after 5s) -->
        <?php if ($msg = session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show js-autodismiss"
                role="alert"
                data-timeout="5000">
                <?= esc($msg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.js-autodismiss').forEach(function(el) {
                        const ms = parseInt(el.getAttribute('data-timeout'), 10) || 5000;

                        function closeAlert() {
                            // Use Bootstrap API if available, else graceful fallback
                            try {
                                bootstrap.Alert.getOrCreateInstance(el).close();
                            } catch (e) {
                                el.classList.remove('show'); // triggers fade-out if .fade is present
                                el.addEventListener('transitionend', () => el.remove(), {
                                    once: true
                                });
                            }
                        }

                        let timer = setTimeout(closeAlert, ms);

                        // Pause on hover/focus, resume on leave/blur
                        const pause = () => {
                            clearTimeout(timer);
                        };
                        const resume = () => {
                            timer = setTimeout(closeAlert, 1500);
                        };

                        el.addEventListener('mouseenter', pause);
                        el.addEventListener('focusin', pause);
                        el.addEventListener('mouseleave', resume);
                        el.addEventListener('focusout', resume);
                    });
                });
            </script>
        <?php endif; ?>


        <!-- Patients Table -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Patients List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">+ New Patient</button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Date of Birth</th>
                                <th>Gender</th>
                                <th>Contact Info</th>
                                <th>Next of Kin Contact</th>
                                <th>Next of Kin Relationship</th>
                                <th style="width:140px">Action</th>
                            </tr>
                        </thead>
                        <tbody id="patientsTbody">
                            <?php
                            // format DOB defensively
                            $fmtDob = static function (?string $d) {
                                try {
                                    return $d ? (new DateTime($d))->format('Y-m-d') : '';
                                } catch (Throwable) {
                                    return esc((string)$d);
                                }
                            };
                            foreach ($patients as $p): ?>
                                <tr data-id="<?= (int)$p['patient_id'] ?>">
                                    <td><?= esc($p['first_name']) ?></td>
                                    <td><?= esc($p['last_name']) ?></td>
                                    <td><?= $fmtDob($p['date_of_birth'] ?? null) ?></td>
                                    <td><?= esc($p['gender']) ?></td>
                                    <td><?= esc($p['contact_info']) ?></td>
                                    <td><?= esc($p['next_of_kin_contact']) ?></td>
                                    <td><?= esc($p['next_of_kin_relationship']) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="w-32-px h-32-px btn btn-info rounded-circle d-inline-flex align-items-center justify-content-center js-view"
                                                title="View Patient" data-bs-toggle="modal" data-bs-target="#viewPatientModal"
                                                data-id="<?= (int)$p['patient_id'] ?>">
                                                <iconify-icon icon="mdi:eye-outline" class="text-white text-lg"></iconify-icon>
                                            </button>

                                            <button type="button" class="w-32-px h-32-px btn btn-success rounded-circle d-inline-flex align-items-center justify-content-center js-edit"
                                                title="Edit Patient" data-bs-toggle="modal" data-bs-target="#editPatientModal"
                                                data-id="<?= (int)$p['patient_id'] ?>">
                                                <iconify-icon icon="mdi:pencil-outline" class="text-white text-lg"></iconify-icon>
                                            </button>

                                            <button type="button" class="w-32-px h-32-px btn btn-danger rounded-circle d-inline-flex align-items-center justify-content-center js-delete"
                                                title="Delete Patient" data-bs-toggle="modal" data-bs-target="#deletePatientModal"
                                                data-id="<?= (int)$p['patient_id'] ?>">
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
                // ---------- Pager ----------
                $rq       = \Config\Services::request();
                $group    = 'patients';
                $current  = (int)($pager->getCurrentPage($group) ?? 1);
                $pageCount = (int)($pager->getPageCount($group) ?? 1);
                $perPage  = (int)($pager->getPerPage($group) ?? ($rq->getGet('per_page') ?? 10));
                $hasPrev  = $current > 1;
                $hasNext  = $current < max(1, $pageCount);

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

                $pad = strlen((string)max(1, $pageCount));
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
                    <nav class="page-nav d-flex align-items-center gap-2" aria-label="Patients pagination">
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

        <?php
        // Build a compact JSON map for modal hydration (XSS-safe)
        $JSON_FLAGS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
        $patientMap = [];
        foreach ($patients as $p) {
            $id = (int)$p['patient_id'];
            $patientMap[$id] = [
                'patient_id'              => $id,
                'first_name'              => (string)$p['first_name'],
                'last_name'               => (string)$p['last_name'],
                'date_of_birth'           => (string)($p['date_of_birth'] ?? ''),
                'gender'                  => (string)$p['gender'],
                'contact_info'            => (string)($p['contact_info'] ?? ''),
                'medical_history'         => (string)($p['medical_history'] ?? ''),
                'next_of_kin_contact'     => (string)($p['next_of_kin_contact'] ?? ''),
                'next_of_kin_relationship' => (string)($p['next_of_kin_relationship'] ?? ''),
            ];
        }
        ?>

        <script>
            // one source of truth for the current page of rows
            window.PATIENTS = <?= json_encode($patientMap, $JSON_FLAGS) ?>;
        </script>

        <!-- ========== ADD PATIENT MODAL ========== -->
        <div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('patients/store') ?>">
                    <?= csrf_field() ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPatientModalLabel">Add New Patient</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact Info</label>
                                    <input type="text" name="contact_info" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Next of Kin Contact</label>
                                    <input type="text" name="next_of_kin_contact" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Next of Kin Relationship</label>
                                    <select name="next_of_kin_relationship" class="form-select" required>
                                        <option value="">Select</option>
                                        <option>Spouse</option>
                                        <option>Child</option>
                                        <option>Parent</option>
                                        <option>Sibling</option>
                                        <option>Guardian</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Medical History</label>
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

        <!-- ========== EDIT PATIENT MODAL ========== -->
        <div class="modal fade" id="editPatientModal" tabindex="-1" aria-labelledby="editPatientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('patients/update') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="patient_id" id="editPatientId">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPatientModalLabel">Edit Patient</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">First Name</label>
                                    <input type="text" id="editFirstName" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" id="editLastName" name="last_name" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" id="editDob" name="date_of_birth" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Gender</label>
                                    <select id="editGender" name="gender" class="form-select" required>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Contact Info</label>
                                    <input type="text" id="editContact" name="contact_info" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Medical History</label>
                                    <textarea id="editMedicalHistory" name="medical_history" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Next of Kin Contact</label>
                                    <input type="text" id="editNextOfKinContact" name="next_of_kin_contact" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Next of Kin Relationship</label>
                                    <select id="editNextOfKinRelationship" name="next_of_kin_relationship" class="form-select" required>
                                        <option>Spouse</option>
                                        <option>Child</option>
                                        <option>Parent</option>
                                        <option>Sibling</option>
                                        <option>Guardian</option>
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

        <!-- ========== DELETE PATIENT MODAL ========== -->
        <div class="modal fade" id="deletePatientModal" tabindex="-1" aria-labelledby="deletePatientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('patients/delete') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="patient_id" id="deletePatientId">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deletePatientModalLabel">Delete Patient</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this patient?
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========== VIEW PATIENT MODAL ========== -->
        <div class="modal fade" id="viewPatientModal" tabindex="-1" aria-labelledby="viewPatientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewPatientModalLabel">Patient Details</h5>
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
        const byId = (id) => document.getElementById(id);
        const tbody = document.getElementById('patientsTbody');
        const map = window.PATIENTS || {};

        // Single delegated handler for action buttons
        tbody?.addEventListener('click', function(e) {
            const btn = e.target.closest('.js-view, .js-edit, .js-delete');
            if (!btn) return;
            const id = btn.getAttribute('data-id');
            const row = map[id];
            if (!row) return;

            // VIEW
            if (btn.classList.contains('js-view')) {
                byId('viewFirstName').textContent = row.first_name || '';
                byId('viewLastName').textContent = row.last_name || '';
                byId('viewDob').textContent = row.date_of_birth || '';
                byId('viewGender').textContent = row.gender || '';
                byId('viewContact').textContent = row.contact_info || '';
                byId('viewMedicalHistory').textContent = row.medical_history || '';
                byId('viewNextOfKinContact').textContent = row.next_of_kin_contact || '';
                byId('viewNextOfKinRelationship').textContent = row.next_of_kin_relationship || '';
            }

            // EDIT
            if (btn.classList.contains('js-edit')) {
                byId('editPatientId').value = row.patient_id;
                byId('editFirstName').value = row.first_name || '';
                byId('editLastName').value = row.last_name || '';
                byId('editDob').value = row.date_of_birth || '';
                byId('editGender').value = row.gender || '';
                byId('editContact').value = row.contact_info || '';
                byId('editMedicalHistory').value = row.medical_history || '';
                byId('editNextOfKinContact').value = row.next_of_kin_contact || '';
                byId('editNextOfKinRelationship').value = row.next_of_kin_relationship || '';
            }

            // DELETE
            if (btn.classList.contains('js-delete')) {
                byId('deletePatientId').value = row.patient_id;
            }
        });
    });
</script>

<?= $this->endSection() ?>