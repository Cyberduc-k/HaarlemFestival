<?php

require_once __DIR__.'/router.php';

$router = Router::instance();

$router->route('/', function($req) {
    require __DIR__.'/controllers/home.php';
});

$router->route('/event/{name}', function($req) {
    require __DIR__.'/controllers/event.php';
    run($req->name);
});

$router->route('*', function($req) {
    require __DIR__.'/views/404.php';
});

$router->run();
