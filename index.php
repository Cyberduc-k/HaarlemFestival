<?php

require_once __DIR__.'/libs/Router/Router.php';

Route::get('/', function() {
    require __DIR__.'/controllers/home.php';
});

Route::get('/event/{name}', function($name) {
    require __DIR__.'/controllers/event.php';
    run($name);
})->word('name');

Route::get('/event/{name}/edit', function($name) {
    require __DIR__.'/controllers/editEvent.php';
    run($name);
})->word('name');

Route::get('/tickets/{name}', function($name) {
    require __DIR__.'/controllers/tickets.php';
    run($name);
})->word('name');

Route::add(['GET', 'POST'], '/login', function() {
    require __DIR__.'/controllers/login.php';
});

Route::add(['GET', 'POST'], '/register', function() {
    require __DIR__.'/controllers/register.php';
});

Route::add(['GET', 'POST'], '/password_reset', function() {
    require __DIR__.'/controllers/resetPassword.php';
});

Route::add(['GET', 'POST'], '/password_reset/confirm', function() {
    require __DIR__.'/controllers/resetPasswordHandler.php';
});

Route::pageNotFound(function() {
    require __DIR__.'/views/404.php';
});

Route::run();
