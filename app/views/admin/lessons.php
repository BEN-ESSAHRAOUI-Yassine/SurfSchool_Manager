<div class="top-bar">
    <h2 style="margin:0;">Lessons</h2>
    <a href="<?= BASE_URL ?>?url=lesson/create" class="action-btn btn-submit">+ Add Lesson</a>
</div>

<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>Level</th>
            <th>Coach</th>
            <th>Price</th>
            <th>Capacity</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($lessons)): ?>
        <tr><td colspan="7" style="text-align:center; color:#94A3B8;">No lessons yet.</td></tr>
    <?php else: ?>
        <?php foreach ($lessons as $l): ?>
        <tr>
            <td><?= htmlspecialchars($l['title']) ?></td>
            <td><?= date('d M Y, H:i', strtotime($l['lesson_date'])) ?></td>
            <td><?= htmlspecialchars($l['lesson_level']) ?></td>
            <td><?= htmlspecialchars($l['coach_name'] ?? 'N/A') ?></td>
            <td><?= number_format((float)$l['price'], 2) ?> €</td>
            <td><?= (int)$l['capacity'] ?></td>
            <td>
                <div class="table-actions">
                    <a href="<?= BASE_URL ?>?url=lesson/edit&id=<?= (int)$l['id_lesson'] ?>"
                       class="action-btn btn-edit">Edit</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
</div>