<?php

require_once __DIR__.'/libs/Router/Router.php';

Route::get('/', function() {
    require __DIR__.'/controllers/home.php';
});

Route::get('/event/{name}', function($name) {
    require __DIR__.'/controllers/event.php';
    run($name);
})->word('name');

Route::pageNotFound(function() {
    require __DIR__.'/views/404.php';
});

Route::run();
