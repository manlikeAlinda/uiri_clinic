<form action="<?= base_url('visitDetails/addDetails') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="type" value="prescription">
    <input type="hidden" name="visit_id" class="set-visit-id">

    <div class="row gx-3 gy-3">
        <div class="col-md-6">
            <label for="drug_id" class="form-label">Drug</label>
            <select id="drug_id" name="drug_id" class="form-select" required>
                <option value="">Select Drug</option>
                <?php foreach ($drugs as $drug): ?>
                    <option value="<?= $drug['drug_id'] ?>"><?= esc($drug['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <?= validation_show_error('drug_id', '_errors/_custom_error') ?>
        </div>

        <div class="col-md-6">
            <label for="dosage" class="form-label">Dosage</label>
            <input id="dosage" name="dosage" type="text" class="form-control" required>
            <?= validation_show_error('dosage', '<div class="text-danger small">', '</div>') ?>
        </div>

        <div class="col-md-6">
            <label for="duration" class="form-label">Duration (days)</label>
            <input id="duration" name="duration" type="text" class="form-control">
            <?= validation_show_error('duration', '<div class="text-danger small">', '</div>') ?>
        </div>

        <div class="col-md-6">
            <label for="quantity" class="form-label">Quantity</label>
            <input id="quantity" name="quantity" type="number" min="1" class="form-control" required>
            <?= validation_show_error('quantity', '<div class="text-danger small">', '</div>') ?>
        </div>

        <div class="col-md-6">
            <label for="route" class="form-label">Route of Administration</label>
            <select id="route" name="route" class="form-select" required>
                <option value="">Select Route</option>
                <option value="Oral">Oral</option>
                <option value="IV">IV</option>
                <option value="IM">IM</option>
                <option value="Topical">Topical</option>
                <option value="Vaginal">Vaginal</option>
                <option value="Others">Others</option>
            </select>
            <?= validation_show_error('route', '<div class="text-danger small">', '</div>') ?>
        </div>

        <div class="col-md-6" id="addOtherRouteGroup" style="display:none;">
            <label for="other_route" class="form-label">Specify Route</label>
            <input id="other_route" name="other_route" type="text" class="form-control">
            <?= validation_show_error('other_route', '<div class="text-danger small">', '</div>') ?>
        </div>

        <div class="col-md-12">
            <label for="instructions" class="form-label">Instructions</label>
            <textarea id="instructions" name="instructions" class="form-control" rows="2"></textarea>
            <?= validation_show_error('instructions', '<div class="text-danger small">', '</div>') ?>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Add Prescription</button>
    </div>
</form>
<script>
    document.getElementById('route').addEventListener('change', function() {
        const other = document.getElementById('addOtherRouteGroup');
        other.style.display = (this.value === 'Others') ? 'block' : 'none';
    });

    document.querySelectorAll('.edit-details-btn').forEach(button => {
    button.addEventListener('click', function () {
        const visitId = this.dataset.visitId;
        document.querySelectorAll('.set-visit-id').forEach(input => {
            input.value = visitId;
        });
    });
});

</script>