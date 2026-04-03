<h2>Admin Dashboard</h2>

<!-- Stats row -->
<div class="dashboard">
    <div class="stat-card">
        <h3>Paid Enrollments</h3>
        <p><?= (int)$paid ?></p>
    </div>
    <div class="stat-card">
        <h3>Pending Payments</h3>
        <p><?= (int)$pending ?></p>
    </div>
    <div class="stat-card">
        <h3>Revenue</h3>
        <p><?= number_format((float)$revenue, 2) ?> €</p>
    </div>
    <div class="stat-card">
        <h3>Pending funds</h3>
        <p><?= number_format((float)$Pendingfunds, 2) ?> €</p>
    </div>
    <div class="stat-card">
        <h3>Total Sessions</h3>
        <p><?= (int)$sessions ?></p>
    </div>
</div>

<h3>All Enrollments</h3>

<?php if (empty($enrollments)): ?>
    <div class="alert alert-info">No enrollments yet.</div>
<?php else: ?>
    <?php foreach ($enrollments as $e): ?>
    <div class="card">
        <strong><?= htmlspecialchars($e['title']) ?></strong>
        <div class="card-meta">
            Level: <?= htmlspecialchars($e['lesson_level']) ?> &nbsp;|&nbsp;
            Coach: <?= htmlspecialchars($e['coach_name'] ?? 'N/A') ?> &nbsp;|&nbsp;
            Student: <?= htmlspecialchars($e['student_name'] ?? 'N/A') ?> &nbsp;|&nbsp;
            Price: <?= number_format((float)$e['price'], 2) ?> €
        </div>

        <div class="card-actions">
            <!-- Payment badge -->
            <span class="badge <?= $e['payment_status'] === 'Paid' ? 'badge-paid' : 'badge-pending' ?>">
                <?= htmlspecialchars($e['payment_status']) ?>
            </span>

            <!-- Mark as Paid button (only show if Pending) -->
            <?php if ($e['payment_status'] === 'Pending'): ?>
            <form method="POST" action="<?= BASE_URL ?>?url=enrollment/update-payment" class="payment-form">
                <input type="hidden" name="csrf"           value="<?= Security::csrf() ?>">
                <input type="hidden" name="id"             value="<?= (int)$e['id_enrollment'] ?>">
                <input type="hidden" name="payment_status" value="Paid">
                <button type="submit" class="action-btn btn-success">Mark as Paid</button>
            </form>
            <?php endif; ?>

            <!-- Delete enrollment — now a POST form for security -->
            <form method="POST" action="<?= BASE_URL ?>?url=enrollment/delete"
                  onsubmit="return confirm('Remove this student from the lesson?')">
                <input type="hidden" name="csrf" value="<?= Security::csrf() ?>">
                <input type="hidden" name="id"   value="<?= (int)$e['id_enrollment'] ?>">
                <button type="submit" class="action-btn btn-delete">Remove</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>