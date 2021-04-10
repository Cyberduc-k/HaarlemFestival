<?php

class Request {
    protected string $uri;
    protected array $params;

    public function __construct() {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->params = [];
    }

    public function __get($k) {
        switch ($k) {
            case 'uri':
                return $this->$k;
            default:
                return isset($this->params[$k]) ? $this->params[$k] : null;
        }
    }

    public function __set($k, $v) {
        $this->params[$k] = $v;
    }
}

?>
