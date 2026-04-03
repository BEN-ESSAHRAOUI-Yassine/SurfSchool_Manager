<h2><?= isset($lesson) ? 'Edit Lesson' : 'Create Lesson' ?></h2>

<div class="form-card">
<form method="POST" action="<?= BASE_URL ?>?url=lesson/<?= isset($lesson) ? 'update' : 'store' ?>">
    <input type="hidden" name="csrf"      value="<?= Security::csrf() ?>">
    <input type="hidden" name="id_lesson" value="<?= (int)($lesson['id_lesson'] ?? 0) ?>">

    <div class="form-group">
        <label for="title">Lesson Title</label>
        <input type="text" id="title" name="title"
               value="<?= htmlspecialchars($lesson['title'] ?? '') ?>" required placeholder="e.g. Beginner Wave Riding">
    </div>

    <div class="form-group">
        <label for="lesson_date">Date & Time</label>
        <input type="datetime-local" id="lesson_date" name="lesson_date"
               value="<?= isset($lesson['lesson_date'])
                   ? date('Y-m-d\TH:i', strtotime($lesson['lesson_date']))
                   : '' ?>" required>
    </div>

    <div class="form-group">
        <label for="lesson_level">Level</label>
        <select id="lesson_level" name="lesson_level">
            <?php foreach (['Beginner', 'Intermediate', 'Advanced'] as $lvl): ?>
            <!-- FIXED: pre-select current level when editing -->
            <option value="<?= $lvl ?>" <?= (($lesson['lesson_level'] ?? '') === $lvl) ? 'selected' : '' ?>>
                <?= $lvl ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="coach_id">Coach</label>
        <select id="coach_id" name="coach_id">
            <?php foreach ($coaches as $c): ?>
            <!-- FIXED: pre-select current coach when editing -->
            <option value="<?= (int)$c['id_user'] ?>"
                <?= (isset($lesson['coach_id']) && (int)$lesson['coach_id'] === (int)$c['id_user']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="price">Price (€)</label>
        <input type="number" id="price" name="price" step="0.01" min="0"
               value="<?= htmlspecialchars($lesson['price'] ?? '') ?>" required placeholder="30.00">
    </div>

    <div class="form-group">
        <label for="capacity">Max Students</label>
        <input type="number" id="capacity" name="capacity" min="1" max="20"
               value="<?= htmlspecialchars($lesson['capacity'] ?? '5') ?>" required>
    </div>

    <div class="flex-gap" style="margin-top:16px;">
        <button type="submit" class="btn-submit">Save Lesson</button>
        <a href="<?= BASE_URL ?>?url=lesson/index" class="action-btn btn-back">Cancel</a>
    </div>
</form>
</div>