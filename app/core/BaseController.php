<?php

class BaseController {

    public function render($view, $data = []) {

        extract($data);

        require VIEW . '/layouts/header.php';

        require VIEW . '/' . $view . '.php';

        require VIEW . '/layouts/footer.php';
    }
}