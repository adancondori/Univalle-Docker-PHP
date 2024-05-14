<?php

class Router {
    private $routes = [];

    public function add($path, $method, $controller, $method_name) {
        $this->routes[$method][$path] = [$controller, $method_name];
    }

    public function dispatch($uri, $method) {
        $path = parse_url($uri, PHP_URL_PATH);
        if (isset($this->routes[$method][$path])) {
            list($controller, $method_name) = $this->routes[$method][$path];
            if (is_string($controller) && class_exists($controller)) {
                $controller = new $controller();
            }
            call_user_func([$controller, $method_name]);
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }
}
