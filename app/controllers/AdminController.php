<?php

class AdminController extends BaseController {

    // Constructor: every admin action requires admin role
    public function __construct() {
        Security::requireRole('admin');
    }

    // List all users with search and sort
    public function users() {
        $search = $_GET['search'] ?? '';
        $sort   = $_GET['sort']   ?? 'id_user';
        $order  = $_GET['order']  ?? 'ASC';

        $users = (new User())->getAll($search, $sort, $order);

        $this->render('admin/users', compact('users', 'search', 'sort', 'order'));
    }

    // List all students
    public function students() {
        $students = (new Student())->getAll();
        $this->render('admin/students', compact('students'));
    }

    // Show create user form
    public function createUser() {
        $this->render('admin/user_form');
    }

    // Save new user
    public function storeUser() {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            Security::setFlash('error', 'Security check failed.');
            header("Location: " . BASE_URL . "?url=admin/create-user");
            exit;
        }

        $name   = Security::sanitize($_POST['name']  ?? '');
        $email  = Security::sanitize($_POST['email'] ?? '');
        $pass   = $_POST['password'] ?? '';

        // Whitelist allowed roles — SECURITY FIX
        $allowedRoles = ['admin', 'coach', 'student'];
        $role = in_array($_POST['role'] ?? '', $allowedRoles) ? $_POST['role'] : 'student';

        if (empty($name) || empty($email) || empty($pass)) {
            Security::setFlash('error', 'Please fill in all fields.');
            header("Location: " . BASE_URL . "?url=admin/create-user");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Security::setFlash('error', 'Invalid email address.');
            header("Location: " . BASE_URL . "?url=admin/create-user");
            exit;
        }

        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            Security::setFlash('error', 'Email already exists.');
            header("Location: " . BASE_URL . "?url=admin/create-user");
            exit;
        }

        $id = $userModel->create($name, $email, password_hash($pass, PASSWORD_DEFAULT), $role);

        // If role is student, create a student profile too
        if ($role === 'student') {
            $country = Security::sanitize($_POST['country'] ?? '');
            $level   = in_array($_POST['level'] ?? '', ['Beginner','Intermediate','Advanced'])
                       ? $_POST['level'] : 'Beginner';
            (new Student())->create($id, $country, $level);
        }

        Security::setFlash('success', 'User created successfully.');
        header("Location: " . BASE_URL . "?url=admin/users");
        exit;
    }

    // Show edit user form
    public function editUser() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            Security::setFlash('error', 'Invalid user.');
            header("Location: " . BASE_URL . "?url=admin/users");
            exit;
        }

        $user = (new User())->findById($id);

        if (!$user) {
            Security::setFlash('error', 'User not found.');
            header("Location: " . BASE_URL . "?url=admin/users");
            exit;
        }

        $this->render('admin/user_form', compact('user'));
    }

    // Save updated user
    public function updateUser() {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            Security::setFlash('error', 'Security check failed.');
            header("Location: " . BASE_URL . "?url=admin/users");
            exit;
        }

        $id    = (int)($_POST['id_user'] ?? 0);
        $name  = Security::sanitize($_POST['name']  ?? '');
        $email = Security::sanitize($_POST['email'] ?? '');

        // Whitelist roles and statuses — SECURITY FIX
        $allowedRoles    = ['admin', 'coach', 'student'];
        $allowedStatuses = ['Enabled', 'Disabled'];
        $role   = in_array($_POST['role']   ?? '', $allowedRoles)    ? $_POST['role']   : 'student';
        $status = in_array($_POST['status'] ?? '', $allowedStatuses) ? $_POST['status'] : 'Enabled';

        if ($id <= 0 || empty($name) || empty($email)) {
            Security::setFlash('error', 'Please fill in all fields.');
            header("Location: " . BASE_URL . "?url=admin/users");
            exit;
        }

        (new User())->update($id, $name, $email, $role, $status);

        Security::setFlash('success', 'User updated successfully.');
        header("Location: " . BASE_URL . "?url=admin/users");
        exit;
    }

    // Toggle user status Enabled <-> Disabled (now via POST for security)
    public function toggleUser() {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            Security::setFlash('error', 'Security check failed.');
            header("Location: " . BASE_URL . "?url=admin/users");
            exit;
        }

        $id         = (int)($_POST['id_user'] ?? 0);
        $currStatus = $_POST['status'] ?? '';
        $newStatus  = $currStatus === 'Enabled' ? 'Disabled' : 'Enabled';

        if ($id <= 0) {
            Security::setFlash('error', 'Invalid user.');
            header("Location: " . BASE_URL . "?url=admin/users");
            exit;
        }

        if ($id === 1) {
            Security::setFlash('error', 'Cannot change the status of the main admin.');
            header("Location: " . BASE_URL . "?url=admin/users");
            exit;
        }

        (new User())->toggleStatus($id, $newStatus);

        Security::setFlash('success', 'User status updated.');
        header("Location: " . BASE_URL . "?url=admin/users");
        exit;
    }
}