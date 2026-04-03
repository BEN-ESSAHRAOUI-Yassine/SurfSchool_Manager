<h2>My Lessons</h2>

<?php if (empty($lessons)): ?>
    <div class="alert alert-info">You are not enrolled in any lessons yet. Please contact an admin.</div>
<?php else: ?>
    <?php foreach ($lessons as $l): ?>
    <div class="card">
        <strong><?= htmlspecialchars($l['title']) ?></strong>
        <div class="card-meta">
            📅 <?= date('d M Y, H:i', strtotime($l['lesson_date'])) ?> &nbsp;|&nbsp;
            🎯 <?= htmlspecialchars($l['lesson_level']) ?> &nbsp;|&nbsp;
            🏄 Coach: <?= htmlspecialchars($l['coach_name'] ?? 'TBA') ?>
        </div>
        <div class="card-actions">
            <span class="badge <?= $l['payment_status'] === 'Paid' ? 'badge-paid' : 'badge-pending' ?>">
                <?= htmlspecialchars($l['payment_status']) ?>
            </span>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>