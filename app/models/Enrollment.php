<?php

class Enrollment {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Create a new enrollment
    public function create($student_id, $lesson_id) {
        $stmt = $this->db->prepare("
            INSERT IGNORE INTO ENROLLMENT (student_id, lesson_id)
            VALUES (?, ?)
        ");
        return $stmt->execute([(int)$student_id, (int)$lesson_id]);
    }

    // Count how many students are in a lesson
    public function countByLesson($lesson_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM ENROLLMENT WHERE lesson_id = ?");
        $stmt->execute([(int)$lesson_id]);
        return (int)$stmt->fetchColumn();
    }

    // Get payment stats for admin dashboard
    public function stats() {
        return [
            'paid'    => $this->db->query("SELECT COUNT(*) FROM ENROLLMENT WHERE payment_status='Paid'")->fetchColumn(),
            'pending' => $this->db->query("SELECT COUNT(*) FROM ENROLLMENT WHERE payment_status='Pending'")->fetchColumn(),
            'revenue' => $this->db->query("
                SELECT COALESCE(SUM(l.price), 0)
                FROM ENROLLMENT e
                JOIN LESSON l ON l.id_lesson = e.lesson_id
                WHERE e.payment_status = 'Paid'
            ")->fetchColumn(),
            'Pendingfunds' => $this->db->query("
                SELECT COALESCE(SUM(l.price), 0)
                FROM ENROLLMENT e
                JOIN LESSON l ON l.id_lesson = e.lesson_id
                WHERE e.payment_status = 'Pending'
            ")->fetchColumn(),
        ];
    }

    // Get enrollment cards for admin dashboard — FIXED: now includes id_enrollment
    public function tickets() {
        $stmt = $this->db->query("
            SELECT
                e.id_enrollment,
                e.payment_status,
                l.id_lesson,
                l.title,
                l.lesson_level,
                l.capacity,
                l.price,
                u.name  as coach_name,
                s2.name as student_name,
                s.id_student,
                COUNT(e2.id_enrollment) as total
            FROM ENROLLMENT e
            JOIN LESSON l       ON l.id_lesson   = e.lesson_id
            LEFT JOIN USER u    ON u.id_user      = l.coach_id
            JOIN STUDENT s      ON s.id_student   = e.student_id
            JOIN USER s2        ON s2.id_user     = s.user_id
            LEFT JOIN ENROLLMENT e2 ON e2.lesson_id = l.id_lesson
            GROUP BY e.id_enrollment
            ORDER BY l.id_lesson, e.id_enrollment
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete enrollment by ID
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM ENROLLMENT WHERE id_enrollment = ?");
        return $stmt->execute([(int)$id]);
    }

    // Update payment status — was MISSING before
    public function updatePayment($id, $status) {
        $stmt = $this->db->prepare("UPDATE ENROLLMENT SET payment_status = ? WHERE id_enrollment = ?");
        return $stmt->execute([$status, (int)$id]);
    }
}