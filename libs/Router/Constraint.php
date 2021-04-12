<?php

class RouteConstraint {
    private $routes;

    function __construct($routes) {
        $this->routes = $routes;
    }

    public function where(string $name, string $regex) {
        foreach ($this->routes as &$route) {
            $idx = (int)$route['keys'][$name];
            $route['where'][$idx] = '/^'.str_replace('/', '\/', $regex).'$/';
        }
    }

    public function word(string $name) {
        $this->where($name, '\w+');
    }

    public function number(string $name) {
        $this->where($name, '\d+');
    }
}

?>
