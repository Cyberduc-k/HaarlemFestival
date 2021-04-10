<?php

class Router {
    private array $exact_matches = [];
    private array $with_params = [];
    private Closure $default_handler;

    public static function instance(): Router {
        static $instance;

        if (is_null($instance)) {
            $instance = new Router();
        }

        return $instance;
    }

    public function route(string $route, callable $f) {
        if ($route == '*') {
            $this->default_handler = $f;
        } else if (false !== preg_match_all('/\{([^}]+)\}/', $route, $match, PREG_PATTERN_ORDER)) {
            $args = $match[1];

            if (count($args) > 0) {
                $keys = [];
                $replacements = [];

                foreach ($args as $k => $arg) {
                    $keys[] = rtrim($arg, '*');
                    $replacements[] = (substr($arg, -1) == '*') ? '(.+?)' : '([^/]+)';
                }

                $matcher = '/^' . str_replace('/', '\/', str_replace($match[0], $replacements, $route)) . '$/';
                $this->with_params[] = new WithParams($matcher, $keys, $f);
            } else {
                $this->exact_matches[$route] = $f;
            }
        } else {
            $this->exact_matches[$route] = $f;
        }
    }

    protected function match($uri, &$params = null) {
        foreach ($this->exact_matches as $route => $handler) {
            if ($uri == $route) {
                return $handler;
            }
        }

        foreach ($this->with_params as $with_params) {
            if (preg_match($with_params->matcher, $uri, $placeholders)) {
                array_shift($placeholders);
                $params = [];

                foreach ($with_params->keys as $k => $arg) {
                    $params[$arg] = $placeholders[$k];
                }

                return $with_params->handler;
            }
        }

        return false;
    }

    public function run() {
        $request = new Request();

        if (false === ($handler = $this->match($request->uri, $params))) {
            return ($this->default_handler)($request);
        }

        if (!is_null($params)) {
            foreach ($params as $k => $v) {
                $request->$k = $v;
            }
        }

        $handler($request);
    }
}

class WithParams {
    public string $matcher;
    public array $keys;
    public Closure $handler;

    public function __construct(string $matcher, array $keys, callable $handler) {
        $this->matcher = $matcher;
        $this->keys = $keys;
        $this->handler = $handler;
    }
}

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
