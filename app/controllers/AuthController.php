<?php

class AuthController extends BaseController {

    public function showLogin() {
        $this->render('auth/login');
    }

    public function showRegister() {
        $this->render('auth/register');
    }

    public function login() {
        // Verify CSRF token
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            Security::setFlash('error', 'Security check failed. Please try again.');
            header("Location: " . BASE_URL . "?url=login");
            exit;
        }

        $email    = Security::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Basic validation
        if (empty($email) || empty($password)) {
            Security::setFlash('error', 'Please fill in all fields.');
            header("Location: " . BASE_URL . "?url=login");
            exit;
        }

        $user = (new User())->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            if ($user['status'] === 'Disabled') {
                Security::setFlash('error', 'Your account has been disabled. Please contact an admin.');
                header("Location: " . BASE_URL . "?url=login");
                exit;
            }

            // Save user info in session
            $_SESSION['user'] = $user;
            header("Location: " . BASE_URL . "?url=dashboard");
            exit;

        } else {
            Security::setFlash('error', 'Invalid email or password.');
            header("Location: " . BASE_URL . "?url=login");
            exit;
        }
    }

    public function register() {
        // Verify CSRF token
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            Security::setFlash('error', 'Security check failed. Please try again.');
            header("Location: " . BASE_URL . "?url=register");
            exit;
        }

        $userModel = new User();

        $name     = Security::sanitize($_POST['name'] ?? '');
        $email    = Security::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $country  = Security::sanitize($_POST['country'] ?? '');
        $level    = $_POST['level'] ?? 'Beginner';

        // Basic validation
        if (empty($name) || empty($email) || empty($password)) {
            Security::setFlash('error', 'Please fill in all required fields.');
            header("Location: " . BASE_URL . "?url=register");
            exit;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Security::setFlash('error', 'Please enter a valid email address.');
            header("Location: " . BASE_URL . "?url=register");
            exit;
        }

        // Validate level
        $allowedLevels = ['Beginner', 'Intermediate', 'Advanced'];
        if (!in_array($level, $allowedLevels)) {
            $level = 'Beginner';
        }

        // Check if email already exists
        if ($userModel->findByEmail($email)) {
            Security::setFlash('error', 'An account with this email already exists.');
            header("Location: " . BASE_URL . "?url=register");
            exit;
        }

        // First user becomes admin, everyone else becomes student
        $role = $userModel->adminExists() ? 'student' : 'admin';

        $id = $userModel->create(
            $name,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $role
        );

        // If student, create student profile
        if ($role === 'student') {
            (new Student())->create($id, $country, $level);
        }

        Security::setFlash('success', 'Account created! Please log in.');
        header("Location: " . BASE_URL . "?url=login");
        exit;
    }
}