<h2>Enroll Students</h2>

<div class="form-card">
<form method="POST" action="<?= BASE_URL ?>?url=enrollment/store">
    <input type="hidden" name="csrf" value="<?= Security::csrf() ?>">

    <div class="form-group">
        <label for="lesson_id">Choose Lesson</label>
        <select id="lesson_id" name="lesson_id" required>
            <option value="">-- Select a lesson --</option>
            <?php foreach ($lessons as $l): ?>
            <option value="<?= (int)$l['id_lesson'] ?>">
                <?= htmlspecialchars($l['title']) ?>
                (<?= htmlspecialchars($l['lesson_level']) ?> —
                <?= date('d M Y', strtotime($l['lesson_date'])) ?>)
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="students">Choose Students <span class="text-muted">(hold Ctrl / Cmd to select multiple — max 5 per lesson)</span></label>
        <select id="students" name="students[]" multiple required
                style="height: 180px;">
            <?php foreach ($students as $s): ?>
            <option value="<?= (int)$s['id_student'] ?>">
                <?= htmlspecialchars($s['name']) ?>
                (<?= htmlspecialchars($s['level']) ?> — <?= htmlspecialchars($s['country']) ?>)
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="flex-gap" style="margin-top:16px;">
        <button type="submit" class="btn-submit">Enroll Students</button>
        <a href="<?= BASE_URL ?>?url=dashboard" class="action-btn btn-back">Cancel</a>
    </div>
</form>
</div>