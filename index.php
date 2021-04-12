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

Route::get('/account', function() {
    require __DIR__.'/controllers/account.php';
});

Route::get('/programme', function() {
    require __DIR__.'/controllers/programme.php';
});

Route::add(['GET', 'POST'], '/users', function() {
    require __DIR__.'/controllers/users.php';
});

array_map(
    function(&$route) { $route->number('id'); },
    Route::add(['GET', 'POST'], '/user/{id?}/edit', function($id = null) {
        require __DIR__.'/controllers/editUser.php';
        run($id);
    })
);

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
