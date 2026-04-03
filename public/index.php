<?php

session_start();

define('BASE_URL', '/SurfSchool-Manager/public/');
define('ROOT', dirname(__DIR__));
define('APP',  ROOT . '/app');
define('VIEW', APP . '/views');

/* ==============================
   AUTOLOADER
   Loads model, controller and core class files automatically
============================== */
spl_autoload_register(function ($class) {
    $paths = [
        APP . '/models/',
        APP . '/controllers/',
        APP . '/core/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

/* ==============================
   GET THE REQUESTED URL
============================== */
$url      = $_GET['url'] ?? 'dashboard';
$url      = trim($url, '/');
$segments = explode('/', $url);

/* ==============================
   PUBLIC ROUTES (no login needed)
============================== */
$publicRoutes = ['login', 'register', 'do-login', 'do-register'];

if (!isset($_SESSION['user']) && !in_array($segments[0], $publicRoutes)) {
    // Redirect to login, save intended URL
    header("Location: " . BASE_URL . "?url=login");
    exit;
}

/* ==============================
   ROUTING
============================== */
switch ($segments[0]) {

    // --- AUTH ---
    case 'login':
        (new AuthController())->showLogin();
        break;

    case 'register':
        (new AuthController())->showRegister();
        break;

    case 'do-login':
        (new AuthController())->login();
        break;

    case 'do-register':
        (new AuthController())->register();
        break;

    case 'logout':
        session_destroy();
        header("Location: " . BASE_URL . "?url=login");
        exit;

    // --- DASHBOARD ---
    case 'dashboard':
        (new DashboardController())->index();
        break;

    // --- PROFILE ---
    case 'profile':
        (new ProfileController())->index();
        break;

    case 'profile-edit':
        (new ProfileController())->edit();
        break;

    case 'profile-update':
        (new ProfileController())->update();
        break;

    // --- ADMIN (admin role only — enforced in AdminController constructor) ---
    case 'admin':
        $action = $segments[1] ?? '';

        switch ($action) {
            case 'users':        (new AdminController())->users();      break;
            case 'students':     (new AdminController())->students();   break;
            case 'create-user':  (new AdminController())->createUser(); break;
            case 'store-user':   (new AdminController())->storeUser();  break;
            case 'edit-user':    (new AdminController())->editUser();   break;
            case 'update-user':  (new AdminController())->updateUser(); break;
            case 'toggle-user':  (new AdminController())->toggleUser(); break;
            default:
                header("Location: " . BASE_URL . "?url=dashboard");
                exit;
        }
        break;

    // --- LESSONS (admin role only — enforced in LessonController constructor) ---
    case 'lesson':
        $action = $segments[1] ?? '';

        switch ($action) {
            case 'index':  (new LessonController())->index();  break;
            case 'create': (new LessonController())->create(); break;
            case 'store':  (new LessonController())->store();  break;
            case 'edit':   (new LessonController())->edit();   break;
            case 'update': (new LessonController())->update(); break;
            default:
                header("Location: " . BASE_URL . "?url=lesson/index");
                exit;
        }
        break;

    // --- ENROLLMENT (admin role only — enforced in EnrollmentController constructor) ---
    case 'enrollment':
        $action = $segments[1] ?? '';

        switch ($action) {
            case 'form':           (new EnrollmentController())->form();          break;
            case 'store':          (new EnrollmentController())->store();         break;
            case 'delete':         (new EnrollmentController())->delete();        break;
            case 'update-payment': (new EnrollmentController())->updatePayment(); break;
            default:
                header("Location: " . BASE_URL . "?url=dashboard");
                exit;
        }
        break;

    // --- CALENDAR ---
    case 'calendar':
        (new CalendarController())->index();
        break;

    // --- 404 ---
    default:
        http_response_code(404);
        echo "<div style='text-align:center; padding:60px; font-family:sans-serif;'>
                <h2>404 — Page not found</h2>
                <a href='" . BASE_URL . "?url=dashboard'>Go to Dashboard</a>
              </div>";
        break;
}