<?php

require_once __DIR__.'/Route.php';
require_once __DIR__.'/Request.php';
require_once __DIR__.'/Constraint.php';

class Router {
    private static Router $instance;

    private array $routes = [];
    private ?Closure $pageNotFound = null;

    public static function instance(): Self {
        if (!isset(Self::$instance)) {
            Self::$instance = new Router();
        }

        return Self::$instance;
    }

    public static function add(array $methods, string $path, callable $handler): RouteConstraint {
        $path = ends_with($path, "/") ? substr($path, 0, strlen($path) - 1) : $path;
        $parts = array_map('RouteData::new', explode('/', $path));
        $params = [];

        foreach ($methods as $method) {
            if (!isset(Self::instance()->routes[$method])) {
                Self::instance()->routes[$method] = Route::base();
            }

            Self::instance()->routes[$method]->add($parts, $handler, $params);
        }

        return new RouteConstraint($params);
    }

    public static function get(string $route, callable $handler): RouteConstraint {
        return self::add(['GET'], $route, $handler);
    }

    public static function post(string $route, callable $handler): RouteConstraint {
        return self::add(['POST'], $route, $handler);
    }

    public static function pageNotFound(callable $handler) {
        self::instance()->pageNotFound = $handler;
    }

    private function matches(string $method, string $path, ?array &$params = null): ?Closure {
        $path = ends_with($path, "/") ? substr($path, 0, strlen($path) - 1) : $path;
        $parts = explode('/', $path);

        if (!isset(Self::instance()->routes[$method])) {
            return null;
        }

        $params ??= [];

        return Self::instance()->routes[$method]->matches($parts, $params);
    }

    public static function run(?string $uri = null) {
        $request = new Request($uri);
        $path = $request->path();
        $method = $request->method();

        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        if (!is_null($handler = Self::instance()->matches($method, $path, $params))) {
            call_user_func_array($handler, $params);
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");

            if (!is_null(Self::instance()->pageNotFound)) {
                call_user_func_array(Self::instance()->pageNotFound, [$path]);
            } else {
                echo "Not Found";
            }
        }
    }
}

?>
