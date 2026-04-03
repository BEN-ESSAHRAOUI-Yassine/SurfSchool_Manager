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