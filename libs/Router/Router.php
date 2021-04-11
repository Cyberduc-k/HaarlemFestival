<?php

require_once __DIR__.'/Request.php';
require_once __DIR__.'/Constraint.php';

class Route {
    private static array $routes = ['GET' => [], 'POST' => []];
    private static ?Closure $pageNotFound = null;

    public static function add(array $methods, string $route, callable $handler): array {
        $constraints = [];

        foreach ($methods as $method) {
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

            array_push($constraints, new RouteConstraint(self::$routes[$method][$count - 1]));
        }

        return $constraints;
    }

    public static function get(string $route, callable $handler): RouteConstraint {
        return self::add(['GET'], $route, $handler)[0];
    }

    public static function post(string $route, callable $handler): RouteConstraint {
        return self::add(['POST'], $route, $handler)[0];
    }

    public static function pageNotFound(callable $handler) {
        self::$pageNotFound = $handler;
    }

    public static function run() {
        $request = new Request();
        $path = $request->path();
        $method = $request->method();
        $path_match_found = false;

        foreach (self::$routes[$method] as $route) {
            if (preg_match($route['matcher'], $path, $matches)) {
                array_shift($matches);

                if (isset($route['where'])) {
                    foreach ($route['where'] as $idx => $matcher) {
                        if (!preg_match($matcher, $matches[$idx])) {
                            continue 2;
                        }
                    }
                }

                $path_match_found = true;
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
