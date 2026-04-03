<div style="max-width: 480px; margin: 0 auto;">
    <h2>Create an Account</h2>

    <div class="form-card">
        <form method="POST" action="<?= BASE_URL ?>?url=do-register">
            <input type="hidden" name="csrf" value="<?= Security::csrf() ?>">

            <div class="form-group">
                <label for="name">Full Name <span style="color:red">*</span></label>
                <input type="text" id="name" name="name" placeholder="John Doe" required>
            </div>

            <div class="form-group">
                <label for="email">Email <span style="color:red">*</span></label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password <span style="color:red">*</span></label>
                <input type="password" id="password" name="password" placeholder="Min 6 characters" required minlength="6">
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" placeholder="France">
            </div>

            <div class="form-group">
                <label for="level">Surf Level</label>
                <select id="level" name="level">
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                </select>
            </div>

            <button type="submit" class="btn-submit" style="width:100%; margin-top:8px;">Create Account</button>
        </form>

        <p style="margin-top:16px; font-size:14px; color:#64748B; text-align:center;">
            Already have an account? <a href="<?= BASE_URL ?>?url=login" style="color:var(--secondary); font-weight:600;">Login</a>
        </p>
    </div>
</div>