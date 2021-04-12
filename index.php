<?php

require_once __DIR__.'/libs/Router/Router.php';

Router::get('/', function() {
    require __DIR__.'/controllers/home.php';
});

Router::add(['GET', 'POST'], '/event/add', function() {
    require __DIR__.'/controllers/addDeleteEvent.php';
});

Router::get('/event/<name>', function($name) {
    require __DIR__.'/controllers/event.php';
    run($name);
})->word('name');

Router::get('/event/<name>/edit', function($name) {
    require __DIR__.'/controllers/editEvent.php';
    run($name);
})->word('name');

Router::get('/tickets/<name>', function($name) {
    require __DIR__.'/controllers/tickets.php';
    run($name);
})->word('name');

Router::get('/account', function() {
    require __DIR__.'/controllers/account.php';
});

Router::get('/programme', function() {
    require __DIR__.'/controllers/programme.php';
});

Router::add(['GET', 'POST'], '/users', function() {
    require __DIR__.'/controllers/users.php';
});

Router::add(['GET', 'POST'], '/user/create', function() {
    require __DIR__.'/controllers/createUser.php';
});

Router::post('/user/delete', function() {
    require __DIR__.'/controllers/deleteUser.php';
});

array_map(
    function(&$route) { $route->number('id'); },
    Router::add(['GET', 'POST'], '/user/<id?>/edit', function($id = null) {
        require __DIR__.'/controllers/editUser.php';
        run($id);
    })
);

Router::add(['GET', 'POST'], '/invoice/create', function() {
    require __DIR__.'/controllers/createInvoice.php';
});

Router::add(['GET', 'POST'], '/api/keys', function() {
    require __DIR__.'/controllers/apiKeys.php';
});

Router::add(['GET', 'POST'], '/login', function() {
    require __DIR__.'/controllers/login.php';
});

Router::add(['GET', 'POST'], '/register', function() {
    require __DIR__.'/controllers/register.php';
});

Router::add(['GET', 'POST'], '/password_reset', function() {
    require __DIR__.'/controllers/resetPassword.php';
});

Router::add(['GET', 'POST'], '/password_reset/confirm', function() {
    require __DIR__.'/controllers/resetPasswordHandler.php';
});

Router::pageNotFound(function() {
    require __DIR__.'/views/404.php';
});

Router::run();
