<?php

class ProfileController extends BaseController {

    public function index() {
        Security::requireLogin();
        $user = $_SESSION['user'];
        $this->render('profile/index', compact('user'));
    }

    public function edit() {
        Security::requireLogin();
        $user = $_SESSION['user'];
        $this->render('profile/edit', compact('user'));
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

        // Refresh session data so header shows new name
        $updated = $userModel->findById($id);
        $_SESSION['user'] = $updated;

        Security::setFlash('success', 'Profile updated successfully.');
        header("Location: " . BASE_URL . "?url=profile");
        exit;
    }
}