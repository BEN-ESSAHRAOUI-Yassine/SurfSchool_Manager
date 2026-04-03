<h2>My Sessions</h2>

<?php if (empty($lessons)): ?>
    <div class="alert alert-info">You have no sessions assigned yet.</div>
<?php else: ?>
    <?php foreach ($lessons as $l): ?>
    <div class="card">
        <strong><?= htmlspecialchars($l['title']) ?></strong>
        <div class="card-meta">
            📅 <?= date('d M Y, H:i', strtotime($l['lesson_date'])) ?> &nbsp;|&nbsp;
            🎯 <?= htmlspecialchars($l['lesson_level']) ?> &nbsp;|&nbsp;
            👥 <?= (int)$l['total_students'] ?> student(s)
        </div>
        <?php if (!empty($l['students'])): ?>
        <div style="margin-top:8px; font-size:13px; color:#475569;">
            <strong>Students:</strong> <?= htmlspecialchars($l['students']) ?>
        </div>
        <?php else: ?>
        <div style="margin-top:8px; font-size:13px; color:#94A3B8;">No students enrolled yet.</div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
<?php endif; ?>