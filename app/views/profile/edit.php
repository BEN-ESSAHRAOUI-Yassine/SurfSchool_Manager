<h2>Edit Profile</h2>

<div class="form-card">
<form method="POST" action="<?= BASE_URL ?>?url=profile-update">
    <input type="hidden" name="csrf" value="<?= Security::csrf() ?>">

    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name"
               value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <!-- Email is read-only — contact admin to change -->
        <input type="email" id="email" value="<?= htmlspecialchars($user['email']) ?>"
               disabled style="background:#F1F5F9; cursor:not-allowed;">
        <small class="text-muted">Contact an admin to change your email.</small>
    </div>

    <div class="form-group">
        <label for="password">New Password <span class="text-muted">(leave blank to keep current)</span></label>
        <input type="password" id="password" name="password"
               placeholder="Enter new password..." minlength="6">
    </div>

    <div class="flex-gap" style="margin-top:16px;">
        <button type="submit" class="btn-submit">Save Changes</button>
        <a href="<?= BASE_URL ?>?url=profile" class="action-btn btn-back">Cancel</a>
    </div>
</form>
</div>