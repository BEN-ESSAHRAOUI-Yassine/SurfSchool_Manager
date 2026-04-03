<?php

class CalendarController extends BaseController {

    public function index() {
        Security::requireLogin();
        $this->render('calendar');
    }
}