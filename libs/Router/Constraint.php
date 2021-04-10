<?php

class RouteConstraint {
    private $route;

    function __construct(&$route) {
        $this->route = &$route;
    }

    public function where(string $name, string $regex) {
        $idx = (int)$this->route['keys'][$name];
        $this->route['where'][$idx] = '/^'.str_replace('/', '\/', $regex).'$/';
    }

    public function word(string $name) {
        $this->where($name, '\w+');
    }

    public function number(string $name) {
        $this->where($name, '\d+');
    }
}

?>
