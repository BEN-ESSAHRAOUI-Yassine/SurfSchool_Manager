<div class="top-bar">
    <h2 style="margin:0;">Users</h2>
    <div class="flex-gap">
        <!-- Search form -->
        <form method="GET" action="" class="search-form">
            <input type="hidden" name="url" value="admin/users">
            <input type="text" name="search" placeholder="Search name or email..."
                   value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit">Search</button>
        </form>
        <a href="<?= BASE_URL ?>?url=admin/create-user" class="action-btn btn-submit">+ Add User</a>
    </div>
</div>

<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($users)): ?>
        <tr><td colspan="5" style="text-align:center; color:#94A3B8;">No users found.</td></tr>
    <?php else: ?>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td>
                <span class="badge <?= $u['status'] === 'Enabled' ? 'badge-enabled' : 'badge-disabled' ?>">
                    <?= htmlspecialchars($u['status']) ?>
                </span>
            </td>
            <td>
                <?php if ((int)$u['id_user'] !== 1): ?>
                <div class="table-actions">
                    <a href="<?= BASE_URL ?>?url=admin/edit-user&id=<?= (int)$u['id_user'] ?>"
                       class="action-btn btn-edit">Edit</a>

                    <!-- Toggle status is now a POST form — no longer a GET link -->
                    <form method="POST" action="<?= BASE_URL ?>?url=admin/toggle-user"
                          onsubmit="return confirm('Change status of <?= htmlspecialchars($u['name']) ?>?')">
                        <input type="hidden" name="csrf"    value="<?= Security::csrf() ?>">
                        <input type="hidden" name="id_user" value="<?= (int)$u['id_user'] ?>">
                        <input type="hidden" name="status"  value="<?= htmlspecialchars($u['status']) ?>">
                        <button type="submit"
                                class="action-btn <?= $u['status'] === 'Enabled' ? 'btn-delete' : 'btn-success' ?>">
                            <?= $u['status'] === 'Enabled' ? 'Disable' : 'Enable' ?>
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
</div>