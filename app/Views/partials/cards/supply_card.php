<div class="card mb-2" id="supply-card-<?= $supply['visit_supplies_id'] ?>">
    <div class="card-body">
        <!-- Scoped validation -->
        <?php if (session()->getFlashdata('errors') && session('errors.origin') === 'supply-' . $supply['visit_supplies_id']): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session('errors.messages') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- 🔹 View Mode -->
        <div class="view-mode">
            <strong><?= esc($supply['supply_name']) ?></strong><br>
            Quantity: <?= esc($supply['quantity_used']) ?> | Type: <?= esc($supply['usage_type']) ?>
            <div class="text-end mt-2">
                <button type="button" class="btn btn-sm btn-outline-primary toggle-edit">Edit</button>
            </div>
        </div>

        <!-- ✏️ Edit Mode -->
        <form class="edit-mode" action="<?= base_url('visitDetails/updateDetail') ?>" method="post" style="display: none;">
            <?= csrf_field() ?>
            <input type="hidden" name="type" value="supply">
            <input type="hidden" name="visit_supplies_id" value="<?= $supply['visit_supplies_id'] ?>">
            <input type="hidden" name="visit_id" value="<?= $supply['visit_id'] ?>">

            <div class="row gy-2">
                <div class="col-md-6">
                    <label class="form-label">Supply</label>
                    <select name="supply_id" class="form-select form-select-sm" required>
                        <?php foreach ($supplies as $s): ?>
                            <option value="<?= $s['supply_id'] ?>" <?= $supply['supply_id'] == $s['supply_id'] ? 'selected' : '' ?>>
                                <?= esc($s['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Quantity Used</label>
                    <input type="number" name="quantity_used" class="form-control form-control-sm" min="1" value="<?= esc($supply['quantity_used']) ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Usage Type</label>
                    <select name="usage_type" class="form-select form-select-sm" required>
                        <option value="standard" <?= $supply['usage_type'] === 'standard' ? 'selected' : '' ?>>Standard</option>
                        <option value="estimated" <?= $supply['usage_type'] === 'estimated' ? 'selected' : '' ?>>Estimated</option>
                        <option value="bulk" <?= $supply['usage_type'] === 'bulk' ? 'selected' : '' ?>>Bulk</option>
                    </select>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-sm btn-success">Save</button>
                <button type="button" class="btn btn-sm btn-secondary cancel-edit">Cancel</button>
            </div>
        </form>
    </div>
</div>

