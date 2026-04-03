<h2>Students</h2>

<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Country</th>
            <th>Level</th>
            <th>Phone</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($students)): ?>
        <tr><td colspan="5" style="text-align:center; color:#94A3B8;">No students found.</td></tr>
    <?php else: ?>
        <?php foreach ($students as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['name']) ?></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <td><?= htmlspecialchars($s['country']) ?></td>
            <td><?= htmlspecialchars($s['level']) ?></td>
            <td><?= htmlspecialchars($s['phone'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
</div>