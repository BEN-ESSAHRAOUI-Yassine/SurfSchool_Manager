<?php

class EnrollmentController extends BaseController {

    public function __construct() {
        Security::requireRole('admin');
    }

    // Show enrollment form
    public function form() {
        $students = (new Student())->getAll();
        $lessons  = (new Lesson())->getAll();
        $this->render('admin/enroll', compact('students', 'lessons'));
    }

    // Save enrollments
    public function store() {
        // CSRF check — was missing before
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            Security::setFlash('error', 'Security check failed.');
            header("Location: " . BASE_URL . "?url=enrollment/form");
            exit;
        }

        $lesson_id = (int)($_POST['lesson_id'] ?? 0);
        $students  = $_POST['students'] ?? [];

        if ($lesson_id <= 0 || empty($students)) {
            Security::setFlash('error', 'Please select a lesson and at least one student.');
            header("Location: " . BASE_URL . "?url=enrollment/form");
            exit;
        }

        $enrollment = new Enrollment();
        $enrolled   = 0;

        foreach ($students as $student_id) {
            // Stop if lesson is full (max 5)
            if ($enrollment->countByLesson($lesson_id) >= 5) {
                break;
            }
            $enrollment->create((int)$student_id, $lesson_id);
            $enrolled++;
        }

        Security::setFlash('success', "$enrolled student(s) enrolled successfully.");
        header("Location: " . BASE_URL . "?url=dashboard");
        exit;
    }

    // Delete an enrollment — now via POST for security
    public function delete() {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            Security::setFlash('error', 'Security check failed.');
            header("Location: " . BASE_URL . "?url=dashboard");
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            Security::setFlash('error', 'Invalid enrollment.');
            header("Location: " . BASE_URL . "?url=dashboard");
            exit;
        }

        (new Enrollment())->delete($id);

        Security::setFlash('success', 'Enrollment deleted.');
        header("Location: " . BASE_URL . "?url=dashboard");
        exit;
    }

    // Update payment status Pending -> Paid
    public function updatePayment() {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            Security::setFlash('error', 'Security check failed.');
            header("Location: " . BASE_URL . "?url=dashboard");
            exit;
        }

        $id     = (int)($_POST['id'] ?? 0);
        $status = $_POST['payment_status'] ?? '';

        $allowed = ['Paid', 'Pending'];
        if ($id <= 0 || !in_array($status, $allowed)) {
            Security::setFlash('error', 'Invalid request.');
            header("Location: " . BASE_URL . "?url=dashboard");
            exit;
        }

        (new Enrollment())->updatePayment($id, $status);

        Security::setFlash('success', 'Payment status updated.');
        header("Location: " . BASE_URL . "?url=dashboard");
        exit;
    }
}