<?php

class LessonController extends BaseController {

    public function __construct() {
        Security::requireRole('admin');
    }

    // List all lessons
    public function index() {
        $lessons = (new Lesson())->getAll();
        $this->render('admin/lessons', compact('lessons'));
    }

    // Show create lesson form
    public function create() {
        $coaches = (new User())->getCoaches();
        $this->render('admin/lesson_form', compact('coaches'));
    }

    // Save new lesson
    public function store() {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            Security::setFlash('error', 'Security check failed.');
            header("Location: " . BASE_URL . "?url=lesson/create");
            exit;
        }

        $title    = Security::sanitize($_POST['title']        ?? '');
        $date     = $_POST['lesson_date']  ?? '';
        $level    = $_POST['lesson_level'] ?? '';
        $coach_id = (int)($_POST['coach_id'] ?? 0);
        $price    = (float)($_POST['price']    ?? 0);
        $capacity = (int)($_POST['capacity']  ?? 0);

        $allowedLevels = ['Beginner', 'Intermediate', 'Advanced'];

        if (empty($title) || empty($date) || !in_array($level, $allowedLevels) || $coach_id <= 0) {
            Security::setFlash('error', 'Please fill in all fields correctly.');
            header("Location: " . BASE_URL . "?url=lesson/create");
            exit;
        }

        (new Lesson())->create([
            'title'        => $title,
            'lesson_date'  => $date,
            'lesson_level' => $level,
            'coach_id'     => $coach_id,
            'price'        => $price,
            'capacity'     => $capacity,
        ]);

        Security::setFlash('success', 'Lesson created successfully.');
        header("Location: " . BASE_URL . "?url=lesson/index");
        exit;
    }

    // Show edit lesson form
    public function edit() {
        $id     = (int)($_GET['id'] ?? 0);
        $lesson = (new Lesson())->find($id); // find() method added to model

        if (!$lesson) {
            Security::setFlash('error', 'Lesson not found.');
            header("Location: " . BASE_URL . "?url=lesson/index");
            exit;
        }

        $coaches = (new User())->getCoaches();
        $this->render('admin/lesson_form', compact('lesson', 'coaches'));
    }

    // Save updated lesson
    public function update() {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            Security::setFlash('error', 'Security check failed.');
            header("Location: " . BASE_URL . "?url=lesson/index");
            exit;
        }

        $id       = (int)($_POST['id_lesson'] ?? 0);
        $title    = Security::sanitize($_POST['title']        ?? '');
        $date     = $_POST['lesson_date']  ?? '';
        $level    = $_POST['lesson_level'] ?? '';
        $coach_id = (int)($_POST['coach_id'] ?? 0);
        $price    = (float)($_POST['price']    ?? 0);
        $capacity = (int)($_POST['capacity']  ?? 0);

        $allowedLevels = ['Beginner', 'Intermediate', 'Advanced'];

        if ($id <= 0 || empty($title) || empty($date) || !in_array($level, $allowedLevels)) {
            Security::setFlash('error', 'Please fill in all fields correctly.');
            header("Location: " . BASE_URL . "?url=lesson/index");
            exit;
        }

        (new Lesson())->update([
            'id_lesson'    => $id,
            'title'        => $title,
            'lesson_date'  => $date,
            'lesson_level' => $level,
            'coach_id'     => $coach_id,
            'price'        => $price,
            'capacity'     => $capacity,
        ]);

        Security::setFlash('success', 'Lesson updated successfully.');
        header("Location: " . BASE_URL . "?url=lesson/index");
        exit;
    }
}