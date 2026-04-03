<?php

class Lesson {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Get all lessons with coach name
    public function getAll() {
        $stmt = $this->db->query("
            SELECT l.*, u.name as coach_name
            FROM LESSON l
            LEFT JOIN USER u ON u.id_user = l.coach_id
            ORDER BY l.lesson_date ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find a single lesson by ID — was MISSING before
    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT l.*, u.name as coach_name
            FROM LESSON l
            LEFT JOIN USER u ON u.id_user = l.coach_id
            WHERE l.id_lesson = ?
        ");
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get lessons for the calendar widget
    public function getCalendarEvents() {
        $stmt = $this->db->query("
            SELECT id_lesson as id, title, lesson_date as start
            FROM LESSON
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new lesson
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO LESSON (title, lesson_date, lesson_level, coach_id, price, capacity)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            Security::sanitize($data['title']),
            $data['lesson_date'],
            $data['lesson_level'],
            (int)$data['coach_id'],
            (float)$data['price'],
            (int)$data['capacity'],
        ]);
    }

    // Update an existing lesson
    public function update($data) {
        $stmt = $this->db->prepare("
            UPDATE LESSON
            SET title=?, lesson_date=?, lesson_level=?, coach_id=?, price=?, capacity=?
            WHERE id_lesson=?
        ");
        $stmt->execute([
            Security::sanitize($data['title']),
            $data['lesson_date'],
            $data['lesson_level'],
            (int)$data['coach_id'],
            (float)$data['price'],
            (int)$data['capacity'],
            (int)$data['id_lesson'],
        ]);
    }

    // Get lessons for a specific student (student dashboard)
    public function getByStudent($user_id) {
        $stmt = $this->db->prepare("
            SELECT
                l.*,
                u.name as coach_name,
                GROUP_CONCAT(s2.name SEPARATOR ', ') as students,
                MAX(e.payment_status) as payment_status,
                e.id_enrollment
            FROM ENROLLMENT e
            JOIN STUDENT s   ON s.id_student = e.student_id
            JOIN USER s2     ON s2.id_user   = s.user_id
            JOIN LESSON l    ON l.id_lesson  = e.lesson_id
            LEFT JOIN USER u ON u.id_user    = l.coach_id
            WHERE s.user_id = ?
            GROUP BY l.id_lesson, e.id_enrollment
        ");
        $stmt->execute([(int)$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get lessons for a specific coach (coach dashboard) — was MISSING before
    public function getByCoach($coach_id) {
        $stmt = $this->db->prepare("
            SELECT
                l.*,
                GROUP_CONCAT(s2.name SEPARATOR ', ') as students,
                COUNT(e.id_enrollment) as total_students
            FROM LESSON l
            LEFT JOIN ENROLLMENT e ON e.lesson_id  = l.id_lesson
            LEFT JOIN STUDENT s    ON s.id_student = e.student_id
            LEFT JOIN USER s2      ON s2.id_user   = s.user_id
            WHERE l.coach_id = ?
            GROUP BY l.id_lesson
            ORDER BY l.lesson_date ASC
        ");
        $stmt->execute([(int)$coach_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Stats for admin dashboard
    public function stats() {
        return [
            'sessions' => $this->db->query("SELECT COUNT(*) FROM LESSON")->fetchColumn(),
            'today'    => $this->db->query("SELECT COUNT(*) FROM LESSON WHERE DATE(lesson_date) = CURDATE()")->fetchColumn(),
            'upcoming' => $this->db->query("SELECT COUNT(*) FROM LESSON WHERE lesson_date > NOW()")->fetchColumn(),
        ];
    }
}