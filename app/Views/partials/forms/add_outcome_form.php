<form action="<?= base_url('visitDetails/addDetails') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="type" value="outcome">
    <input type="hidden" name="visit_id" class="set-visit-id">

    <div class="row gx-3 gy-3">
        <div class="col-md-6">
            <label for="outcome" class="form-label">Outcome</label>
            <select id="outcome" name="outcome" class="form-select" required>
                <option value="">Select Outcome</option>
                <option value="Referred">Referred</option>
                <option value="Discharged">Discharged</option>
            </select>
            <?= validation_show_error('outcome', '<div class="text-danger small">', '</div>') ?>
        </div>

        <div class="col-md-6">
            <label for="treatment_notes" class="form-label">Treatment Notes</label>
            <textarea id="treatment_notes" name="treatment_notes" class="form-control" rows="2"></textarea>
            <?= validation_show_error('treatment_notes', '<div class="text-danger small">', '</div>') ?>
        </div>

        <div class="col-md-6" id="referralReasonGroup" style="display:none;">
            <label for="referral_reason" class="form-label">Referral Reason</label>
            <textarea id="referral_reason" name="referral_reason" class="form-control" rows="2"></textarea>
        </div>

        <div class="col-md-6" id="dischargeTimeGroup" style="display:none;">
            <label for="discharge_time" class="form-label">Discharge Time</label>
            <input id="discharge_time" name="discharge_time" type="datetime-local" class="form-control">
        </div>

        <div class="col-md-6" id="dischargeConditionGroup" style="display:none;">
            <label for="discharge_condition" class="form-label">Condition at Discharge</label>
            <textarea id="discharge_condition" name="discharge_condition" class="form-control" rows="2"></textarea>
        </div>

        <div class="col-md-6">
            <label for="return_date" class="form-label">Return Date</label>
            <input id="return_date" name="return_date" type="date" class="form-control">
        </div>

        <div class="col-md-6">
            <label for="follow_up_notes" class="form-label">Follow-Up Notes</label>
            <textarea id="follow_up_notes" name="follow_up_notes" class="form-control" rows="2"></textarea>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Save Outcome</button>
    </div>
</form>

<script>
    document.getElementById('outcome').addEventListener('change', function() {
        const referral = document.getElementById('referralReasonGroup');
        const discharge = document.getElementById('dischargeTimeGroup');
        const condition = document.getElementById('dischargeConditionGroup');

        referral.style.display = this.value === 'Referred' ? 'block' : 'none';
        discharge.style.display = this.value === 'Discharged' ? 'block' : 'none';
        condition.style.display = this.value === 'Discharged' ? 'block' : 'none';
    });
</script>