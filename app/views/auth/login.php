<div class="login-page">
    <div class="login-left">
        <h1>🏄 Surf School</h1>
        <p>Manage lessons, students, and sessions all in one place.</p>
    </div>

    <div class="login-right">
        <div class="login-box">
            <h2>Welcome back</h2>

            <form method="POST" action="<?= BASE_URL ?>?url=do-login">
                <input type="hidden" name="csrf" value="<?= Security::csrf() ?>">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <button class="btn-login" type="submit">Login</button>
            </form>

            <p>Don't have an account? <a href="<?= BASE_URL ?>?url=register">Register here</a></p>
        </div>
    </div>
</div>