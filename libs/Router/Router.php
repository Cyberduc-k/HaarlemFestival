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

            if (preg_match_all('/\{([^}]+)\}|\/\{([^}]+\?)\}/', $route, $match, PREG_PATTERN_ORDER)) {
                $args = $match[1];
                $opt_args = $match[2];

                if (count($args) > 0) {
                    $replacements = [];

                    foreach ($args as $k => $arg) {
                        if ($arg !== '') {
                            $keys[rtrim($arg, '*')] = $k;
                            $replacements[$k] = (substr($arg, -1) == '*') ? '(.*?)' : '([^/]+)';
                        }
                    }

                    foreach ($replacements as $k => $repl) {
                        $matcher = str_replace($match[0][$k], $repl, $matcher);
                    }
                }

                if (count($opt_args) > 0) {
                    $replacements = [];

                    foreach ($opt_args as $k => $arg) {
                        if ($arg !== '') {
                            $keys[rtrim($arg, '*?')] = $k;
                            $replacements[$k] = (substr($arg, -2) == '*?') ? '(?:/(.*?))?' : '(?:/([^/]+))?';
                        }
                    }

                    foreach ($replacements as $k => $repl) {
                        $matcher = str_replace($match[0][$k], $repl, $matcher);
                    }
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

        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        foreach (self::$routes[$method] as $route) {
            if (preg_match($route['matcher'], $path, $matches)) {
                array_shift($matches);

                if (isset($route['where'])) {
                    foreach ($route['where'] as $idx => $matcher) {
                        if (isset($matches[$idx]) && !preg_match($matcher, $matches[$idx])) {
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
