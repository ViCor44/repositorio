<?php

namespace Core;

class Controller {

    protected function view(string $view, array $data = [], string $layout = 'layouts/main') {

        extract($data);

        ob_start();
        require __DIR__ . "/../App/Views/$view.php";
        $content = ob_get_clean();

        require __DIR__ . "/../App/Views/$layout.php";
    }

    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }
}
