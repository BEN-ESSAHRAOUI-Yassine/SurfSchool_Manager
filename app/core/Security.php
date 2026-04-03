<?php

class Security {

    // Clean user input to prevent XSS
    public static function sanitize($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    // Generate CSRF token (stored in session)
    public static function csrf() {
        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    // Check if the submitted CSRF token matches
    public static function verifyCsrf($token) {
        return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
    }

    // Check if user is logged in, redirect to login if not
    public static function requireLogin() {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "?url=login");
            exit;
        }
    }

    // Check if user has a required role, redirect to dashboard if not
    public static function requireRole($role) {
        self::requireLogin();
        if ($_SESSION['user']['role'] !== $role) {
            header("Location: " . BASE_URL . "?url=dashboard");
            exit;
        }
    }

    // Save a flash message to session (shown once on next page)
    public static function setFlash($type, $message) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    // Get and clear the flash message
    public static function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}