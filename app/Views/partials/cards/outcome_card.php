<?php if ($outcome): ?>
<div class="card mb-2" id="outcome-card-<?= $outcome['outcome_id'] ?>">
    <div class="card-body">
        <!-- Inline Scoped Validation -->
        <?php if (session()->getFlashdata('errors') && session('errors.origin') === 'outcome-' . $outcome['outcome_id']): ?>
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
            <p><strong>Outcome:</strong> <?= esc($outcome['outcome']) ?></p>
            <p><strong>Treatment Notes:</strong> <?= esc($outcome['treatment_notes']) ?></p>
            <?php if ($outcome['outcome'] === 'Referred'): ?>
                <p><strong>Referral Reason:</strong> <?= esc($outcome['referral_reason']) ?></p>
            <?php endif; ?>
            <?php if ($outcome['outcome'] === 'Discharged'): ?>
                <p><strong>Discharge Time:</strong> <?= esc($outcome['discharge_time']) ?></p>
                <p><strong>Discharge Condition:</strong> <?= esc($outcome['discharge_condition']) ?></p>
            <?php endif; ?>
            <p><strong>Return Date:</strong> <?= esc($outcome['return_date']) ?></p>
            <p><strong>Follow-up Notes:</strong> <?= esc($outcome['follow_up_notes']) ?></p>

            <div class="text-end">
                <button class="btn btn-sm btn-outline-primary toggle-edit">Edit</button>
            </div>
        </div>

        <!-- INLINE EDIT FORM -->
        <form class="edit-mode mt-3" action="<?= base_url('visitDetails/updateDetail') ?>" method="post" style="display: none;">
            <?= csrf_field() ?>
            <input type="hidden" name="type" value="outcome">
            <input type="hidden" name="visit_id" value="<?= $outcome['visit_id'] ?>">
            <input type="hidden" name="outcome_id" value="<?= $outcome['outcome_id'] ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Outcome</label>
                    <select name="outcome" id="outcomeSelect-<?= $outcome['outcome_id'] ?>" class="form-select" required>
                        <option value="Discharged" <?= $outcome['outcome'] === 'Discharged' ? 'selected' : '' ?>>Discharged</option>
                        <option value="Referred" <?= $outcome['outcome'] === 'Referred' ? 'selected' : '' ?>>Referred</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Discharge Time</label>
                    <input type="datetime-local" name="discharge_time" class="form-control"
                           value="<?= esc($outcome['discharge_time']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Discharge Condition</label>
                    <input type="text" name="discharge_condition" class="form-control"
                           value="<?= esc($outcome['discharge_condition']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Referral Reason</label>
                    <input type="text" name="referral_reason" class="form-control"
                           value="<?= esc($outcome['referral_reason']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Return Date</label>
                    <input type="date" name="return_date" class="form-control"
                           value="<?= esc($outcome['return_date']) ?>">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Treatment Notes</label>
                    <textarea name="treatment_notes" class="form-control" rows="2"><?= esc($outcome['treatment_notes']) ?></textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Follow-up Notes</label>
                    <textarea name="follow_up_notes" class="form-control" rows="2"><?= esc($outcome['follow_up_notes']) ?></textarea>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-sm btn-success">Save</button>
                <button type="button" class="btn btn-sm btn-secondary cancel-edit">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<div class="alert alert-info">
    No outcome recorded yet for this visit.
</div>
<?php endif; ?>
