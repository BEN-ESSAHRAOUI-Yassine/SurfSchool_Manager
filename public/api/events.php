<?php

// Start session so we can check login status
session_start();

define('ROOT', dirname(__DIR__, 2));
define('APP',  ROOT . '/app');
define('BASE_URL', '/SurfSchool-Manager-PRJ/public/');

// Only logged-in users can fetch calendar events
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require APP . '/core/database.php';
require APP . '/core/Security.php';
require APP . '/models/Lesson.php';

header('Content-Type: application/json');

$events = (new Lesson())->getCalendarEvents();

echo json_encode($events);