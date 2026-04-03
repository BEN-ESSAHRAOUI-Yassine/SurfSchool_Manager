<?php

class Student {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Create a student profile linked to a user
    public function create($user_id, $country, $level) {
        $stmt = $this->db->prepare("
            INSERT INTO STUDENT (user_id, country, level) VALUES (?, ?, ?)
        ");
        $stmt->execute([(int)$user_id, $country, $level]);
    }

    // Find a student record by their user_id
    public function findByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM STUDENT WHERE user_id = ?");
        $stmt->execute([(int)$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update level (and optionally country) for a student
    public function updateLevel($user_id, $level, $country) {
        $stmt = $this->db->prepare("UPDATE STUDENT SET level = ?, country = ? WHERE user_id = ?");
        return $stmt->execute([$level, $country, (int)$user_id]);
    }

    // Get all students with their user info
    public function getAll() {
        $stmt = $this->db->query("
            SELECT s.*, u.name, u.email
            FROM STUDENT s
            JOIN USER u ON u.id_user = s.user_id
            ORDER BY u.name ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}