<h2><?= isset($user) ? 'Edit User' : 'Create User' ?></h2>

<div class="form-card">
<form method="POST" action="<?= BASE_URL ?>?url=admin/<?= isset($user) ? 'update-user' : 'store-user' ?>">
    <input type="hidden" name="csrf"    value="<?= Security::csrf() ?>">
    <input type="hidden" name="id_user" value="<?= (int)($user['id_user'] ?? 0) ?>">

    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name"
               value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
    </div>

    <?php if (!isset($user)): ?>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Min 6 characters" required minlength="6">
    </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="role">Role</label>
        <select id="role" name="role">
            <?php foreach (['admin', 'coach', 'student'] as $r): ?>
            <!-- FIXED: pre-select current role when editing -->
            <option value="<?= $r ?>" <?= (($user['role'] ?? '') === $r) ? 'selected' : '' ?>>
                <?= ucfirst($r) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status">
            <?php foreach (['Enabled', 'Disabled'] as $s): ?>
            <!-- FIXED: pre-select current status when editing -->
            <option value="<?= $s ?>" <?= (($user['status'] ?? 'Enabled') === $s) ? 'selected' : '' ?>>
                <?= $s ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Show country/level fields only when creating a student via admin -->
    <div id="student-fields" style="display:none;">
        <div class="form-group">
            <label for="country">Country</label>
            <input type="text" id="country" name="country" placeholder="France">
        </div>
        <div class="form-group">
            <label for="level">Surf Level</label>
            <select id="level" name="level">
                <option>Beginner</option>
                <option>Intermediate</option>
                <option>Advanced</option>
            </select>
        </div>
    </div>

    <div class="flex-gap" style="margin-top:16px;">
        <button type="submit" class="btn-submit">Save User</button>
        <a href="<?= BASE_URL ?>?url=admin/users" class="action-btn btn-back">Cancel</a>
    </div>
</form>
</div>

<script>
// Show student fields when role = student (only on create form)
const roleSelect = document.getElementById('role');
const studentFields = document.getElementById('student-fields');

function toggleStudentFields() {
    if (roleSelect && studentFields) {
        studentFields.style.display = roleSelect.value === 'student' ? 'block' : 'none';
    }
}

if (roleSelect) {
    roleSelect.addEventListener('change', toggleStudentFields);
    toggleStudentFields(); // run once on page load
}
</script>