<?php

require_once __DIR__.'/Request.php';
require_once __DIR__.'/Constraint.php';

class Route {
    private static array $routes = ['GET' => [], 'POST' => []];
    private static ?Closure $pageNotFound = null;

    public static function add(string $route, callable $handler, string $method): RouteConstraint {
        $matcher = $route;
        $keys = [];

        if (preg_match_all('/\{([^}]+)\}/', $route, $match, PREG_PATTERN_ORDER)) {
            $args = $match[1];

            if (count($args) > 0) {
                $replacements = [];

                foreach ($args as $k => $arg) {
                    $keys[rtrim($arg, '*')] = $k;
                    $replacements[] = (substr($arg, -1) == '*') ? '(.+?)' : '([^/]+)';
                }

                $matcher = str_replace($match[0], $replacements, $route);
            }
        }

        $count = array_push(self::$routes[$method], [
            'matcher' => '/^'.str_replace('/', '\/', $matcher).'$/',
            'handler' => $handler,
            'keys' => $keys,
        ]);

        return new RouteConstraint(self::$routes[$method][$count - 1]);
    }

    public static function get(string $route, callable $handler): RouteConstraint {
        return self::add($route, $handler, 'GET');
    }

    public static function post(string $route, callable $handler): RouteConstraint {
        return self::add($route, $handler, 'POST');
    }

    public static function pageNotFound(callable $handler) {
        self::$pageNotFound = $handler;
    }

    public static function run() {
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);

        if (isset($parsed_url['path'])) {
            $path = $parsed_url['path'];
        } else {
            $path = '/';
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $path_match_found = false;

        foreach (self::$routes[$method] as $route) {
            if (preg_match($route['matcher'], $path, $matches)) {
                $path_match_found = true;
                array_shift($matches);

                if (isset($route['where'])) {
                    foreach ($route['where'] as $idx => $matcher) {
                        if (!preg_match($matcher, $matches[$idx])) {
                            continue 2;
                        }
                    }
                }

                call_user_func_array($route['handler'], $matches);
                break;
            }
        }

        if (!$path_match_found) {
            header("HTTP/1.0 404 Not Found");

            if (self::$pageNotFound) {
                call_user_func_array(self::$pageNotFound, [$path]);
            }
        }
    }
}

?>
