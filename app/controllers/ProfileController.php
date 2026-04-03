<?php

class ProfileController extends BaseController {

    public function index() {
        Security::requireLogin();
        $user    = $_SESSION['user'];
        // Load student record (level, country) if the user is a student
        $student = null;
        if ($user['role'] === 'student') {
            $student = (new Student())->findByUserId($user['id_user']);
        }
        $this->render('profile/index', compact('user', 'student'));
    }

    public function edit() {
        Security::requireLogin();
        $user    = $_SESSION['user'];
        $student = null;
        if ($user['role'] === 'student') {
            $student = (new Student())->findByUserId($user['id_user']);
        }
        $this->render('profile/edit', compact('user', 'student'));
    }

    public function update() {
        Security::requireLogin();

        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            Security::setFlash('error', 'Security check failed.');
            header("Location: " . BASE_URL . "?url=profile-edit");
            exit;
        }

        $id   = $_SESSION['user']['id_user'];
        $name = Security::sanitize($_POST['name'] ?? '');

        if (empty($name)) {
            Security::setFlash('error', 'Name cannot be empty.');
            header("Location: " . BASE_URL . "?url=profile-edit");
            exit;
        }

        $userModel = new User();

        // Handle optional password change
        if (!empty($_POST['password'])) {
            $userModel->updateWithPassword($id, $name, password_hash($_POST['password'], PASSWORD_DEFAULT));
        } else {
            $userModel->updateName($id, $name);
        }

        // If student, also update level and country
        if ($_SESSION['user']['role'] === 'student') {
            $allowedLevels = ['Beginner', 'Intermediate', 'Advanced'];
            $level   = in_array($_POST['level'] ?? '', $allowedLevels) ? $_POST['level'] : 'Beginner';
            $country = Security::sanitize($_POST['country'] ?? '');
            (new Student())->updateLevel($id, $level, $country);
        }

        // Refresh session data so header shows new name
        $updated = $userModel->findById($id);
        $_SESSION['user'] = $updated;

        Security::setFlash('success', 'Profile updated successfully.');
        header("Location: " . BASE_URL . "?url=profile");
        exit;
    }
}