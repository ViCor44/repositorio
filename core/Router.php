<?php
namespace Core;

class Router {

    protected array $routes = [];

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch($uri, $method) {
        $path = parse_url($uri, PHP_URL_PATH);

        $base = '/repositorio/public';

        if (str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }

        if ($path === '') {
            $path = '/';
        }

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo "404 - Página não encontrada";
            return;
        }

        [$controller, $methodAction] =
            explode('@', $this->routes[$method][$path]);

        $controller = "App\\Controllers\\$controller";

        call_user_func([new $controller, $methodAction]);
    }
}
