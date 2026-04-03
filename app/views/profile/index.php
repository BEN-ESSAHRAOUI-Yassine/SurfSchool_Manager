<h2>My Profile</h2>

<div class="profile-card">
    <div class="profile-field">
        <div class="field-label">Full Name</div>
        <div class="field-value"><?= htmlspecialchars($user['name']) ?></div>
    </div>

    <div class="profile-field">
        <div class="field-label">Email</div>
        <div class="field-value"><?= htmlspecialchars($user['email']) ?></div>
    </div>

    <div class="profile-field">
        <div class="field-label">Role</div>
        <div class="field-value"><?= ucfirst(htmlspecialchars($user['role'])) ?></div>
    </div>

    <?php if ($user['role'] === 'student' && $student): ?>
    <div class="profile-field">
        <div class="field-label">Surf Level</div>
        <div class="field-value">
            <?php
                $levelColors = [
                    'Beginner'     => '#10B981',
                    'Intermediate' => '#F97316',
                    'Advanced'     => '#0EA5E9',
                ];
                $color = $levelColors[$student['level']] ?? '#64748B';
            ?>
            <span class="badge" style="background: <?= $color ?>;">
                <?= htmlspecialchars($student['level']) ?>
            </span>
        </div>
    </div>

    <div class="profile-field">
        <div class="field-label">Country</div>
        <div class="field-value"><?= htmlspecialchars($student['country'] ?: '—') ?></div>
    </div>
    <?php endif; ?>

    <div class="profile-field">
        <div class="field-label">Account Status</div>
        <div class="field-value">
            <span class="badge <?= $user['status'] === 'Enabled' ? 'badge-enabled' : 'badge-disabled' ?>">
                <?= htmlspecialchars($user['status']) ?>
            </span>
        </div>
    </div>

    <div class="profile-field">
        <div class="field-label">Member Since</div>
        <div class="field-value"><?= date('d M Y', strtotime($user['created_at'])) ?></div>
    </div>

    <div style="margin-top: 20px;">
        <a href="<?= BASE_URL ?>?url=profile-edit" class="action-btn btn-edit">Edit Profile</a>
    </div>
</div>