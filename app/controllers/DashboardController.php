<?php

class DashboardController extends BaseController {

    public function index() {
        Security::requireLogin();

        $role = $_SESSION['user']['role'];

        if ($role === 'admin') {

            $stats       = array_merge(
                (new Enrollment())->stats(),
                (new Lesson())->stats()
            );
            $enrollments = (new Enrollment())->tickets();

            extract($stats);

            $this->render('admin/dashboard', compact(
                'paid', 'pending', 'revenue', 'Pendingfunds','sessions', 'enrollments'
            ));

        } elseif ($role === 'coach') {

            $user_id = $_SESSION['user']['id_user'];
            // FIXED: coaches use getByCoach(), not getByStudent()
            $lessons = (new Lesson())->getByCoach($user_id);

            $this->render('coach/dashboard', compact('lessons'));

        } else {

            $user_id = $_SESSION['user']['id_user'];
            $lessons = (new Lesson())->getByStudent($user_id);

            $this->render('student/dashboard', compact('lessons'));
        }
    }
}