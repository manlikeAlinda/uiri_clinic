<form method="post" action="<?= base_url('visitDetails/addDetails') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="type" value="supply">
    <input type="hidden" name="visit_id" value="<?= request()->getPost('visit_id') ?? '' ?>">
    <div class="row g-3">
        <div class="col-md-6">
            <label>Supply</label>
            <select name="supply_id" class="form-select" required>
                <?php foreach ($supplies as $supply): ?>
                    <option value="<?= $supply['supply_id'] ?>"><?= esc($supply['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6"><label>Quantity Used</label><input name="quantity_used" type="number" class="form-control" required></div>
        <div class="col-md-6">
            <label>Usage Type</label>
            <select name="usage_type" class="form-select" required>
                <option value="standard">Standard</option>
                <option value="estimated">Estimated</option>
                <option value="bulk">Bulk</option>
            </select>
        </div>
        <div class="text-end mt-2">
            <button type="submit" class="btn btn-success btn-sm">Record Supply</button>
        </div>
    </div>
</form>