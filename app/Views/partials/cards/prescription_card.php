<div class="card mb-2" id="prescription-card-<?= esc($p['prescription_id']) ?>">
    <div class="card-body">
        <!-- Scoped Validation Errors -->
        <?php if (session()->getFlashdata('errors') && session('errors.origin') === 'prescription-' . $p['prescription_id']): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session('errors.messages') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- READ-ONLY VIEW -->
        <div class="view-mode">
            <strong><?= esc($p['drug_name']) ?></strong><br>
            Dosage: <?= esc($p['dosage']) ?> | Qty: <?= esc($p['quantity']) ?><br>
            Duration: <?= esc($p['duration']) ?> days | Route: <?= esc($p['route']) ?><br>
            <?= $p['instructions'] ? 'Instructions: ' . esc($p['instructions']) : '' ?>

            <div class="text-end mt-2">
                <button class="btn btn-sm btn-outline-primary toggle-edit">Edit</button>
            </div>
        </div>

        <!-- INLINE EDIT FORM -->
        <form class="edit-mode" action="<?= site_url('visitdetails/updateDetail') ?>" method="post" style="display: none;">
            <?= csrf_field() ?>
            <input type="hidden" name="type" value="prescription">
            <input type="hidden" name="prescription_id" value="<?= esc($p['prescription_id']) ?>">
            <input type="hidden" name="visit_id" value="<?= esc($p['visit_id']) ?>">

            <div class="row gx-2 gy-2">
                <!-- DRUG SELECT -->
                <div class="col-md-4">
                    <label class="form-label">Drug</label>
                    <select name="drug_id" class="form-select form-select-sm" required>
                        <?php foreach ($drugs as $drug): ?>
                            <option value="<?= esc($drug['drug_id']) ?>" <?= $p['drug_id'] == $drug['drug_id'] ? 'selected' : '' ?>>
                                <?= esc($drug['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- DOSAGE -->
                <div class="col-md-4">
                    <label class="form-label">Dosage</label>
                    <input type="text" name="dosage" class="form-control form-control-sm" value="<?= esc($p['dosage']) ?>" required>
                </div>

                <!-- QUANTITY -->
                <div class="col-md-4">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" min="1" class="form-control form-control-sm" value="<?= esc($p['quantity']) ?>" required>
                </div>

                <!-- DURATION -->
                <div class="col-md-4">
                    <label class="form-label">Duration (days)</label>
                    <input type="text" name="duration" class="form-control form-control-sm" value="<?= esc($p['duration']) ?>" required>
                </div>

                <!-- ROUTE -->
                <div class="col-md-4">
                    <label class="form-label">Route</label>
                    <select name="route" class="form-select form-select-sm" required>
                        <option value="Oral" <?= $p['route'] === 'Oral' ? 'selected' : '' ?>>Oral</option>
                        <option value="IV" <?= $p['route'] === 'IV' ? 'selected' : '' ?>>IV</option>
                        <option value="IM" <?= $p['route'] === 'IM' ? 'selected' : '' ?>>IM</option>
                        <option value="Topical" <?= $p['route'] === 'Topical' ? 'selected' : '' ?>>Topical</option>
                        <option value="Vaginal" <?= $p['route'] === 'Vaginal' ? 'selected' : '' ?>>Vaginal</option>
                        <option value="Others" <?= $p['route'] === 'Others' ? 'selected' : '' ?>>Others</option>
                    </select>
                </div>

                <!-- OTHER ROUTE (if selected) -->
                <div class="col-md-4" id="editOtherRouteGroup-<?= esc($p['prescription_id']) ?>" style="display: <?= $p['route'] === 'Others' ? 'block' : 'none' ?>;">
                    <label class="form-label">Specify Route</label>
                    <input type="text" name="other_route" class="form-control form-control-sm" value="<?= esc($p['other_route']) ?>">
                </div>

                <!-- INSTRUCTIONS -->
                <div class="col-md-12">
                    <label class="form-label">Instructions</label>
                    <input type="text" name="instructions" class="form-control form-control-sm" value="<?= esc($p['instructions']) ?>">
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-sm btn-success">Save</button>
                <button type="button" class="btn btn-sm btn-secondary cancel-edit">Cancel</button>
            </div>
        </form>
    </div>
</div>