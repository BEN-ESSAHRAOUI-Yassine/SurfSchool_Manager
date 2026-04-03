<?php

class User {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Find user by email
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM USER WHERE email = ? AND deleted_at IS NULL");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Find user by ID — was MISSING before
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM USER WHERE id_user = ? AND deleted_at IS NULL");
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user
    public function create($name, $email, $password, $role) {
        $stmt = $this->db->prepare("INSERT INTO USER (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role]);
        return $this->db->lastInsertId();
    }

    // Update user info (admin)
    public function update($id, $name, $email, $role, $status) {
        $stmt = $this->db->prepare("
            UPDATE USER SET name=?, email=?, role=?, status=? WHERE id_user=?
        ");
        return $stmt->execute([$name, $email, $role, $status, (int)$id]);
    }

    // Update profile name only
    public function updateName($id, $name) {
        $stmt = $this->db->prepare("UPDATE USER SET name=? WHERE id_user=?");
        return $stmt->execute([$name, (int)$id]);
    }

    // Update profile name + password
    public function updateWithPassword($id, $name, $hashedPassword) {
        $stmt = $this->db->prepare("UPDATE USER SET name=?, password=? WHERE id_user=?");
        return $stmt->execute([$name, $hashedPassword, (int)$id]);
    }

    // Check if any admin exists (used on first register)
    public function adminExists() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM USER WHERE role='admin'");
        return $stmt->fetchColumn() > 0;
    }

    // Get all users with optional search and sort
    public function getAll($search = '', $sort = 'id_user', $order = 'ASC', $limit = 50, $offset = 0) {

        // Only allow safe sort columns
        $sortMap = [
            'id_user' => 'id_user',
            'name'    => 'name',
            'email'   => 'email',
            'role'    => 'role',
            'status'  => 'status',
        ];

        $sort  = $sortMap[$sort]  ?? 'id_user';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT * FROM USER WHERE deleted_at IS NULL";

        if ($search) {
            $sql .= " AND (name LIKE :search OR email LIKE :search)";
        }

        $sql .= " ORDER BY $sort $order LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        if ($search) {
            $stmt->bindValue(':search', "%$search%");
        }

        $stmt->bindValue(':limit',  (int)$limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Toggle user Enabled/Disabled
    public function toggleStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE USER SET status=? WHERE id_user=?");
        return $stmt->execute([$status, (int)$id]);
    }

    // Get all coaches (for lesson assignment dropdown)
    public function getCoaches() {
        $stmt = $this->db->query("SELECT id_user, name FROM USER WHERE role='coach' AND status='Enabled' AND deleted_at IS NULL");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}