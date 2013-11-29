<?php

require '../craft/hello.php';

// setup router
$router = new craft\Router([
    '/'        => 'my\logic\Front::hello',
    '/404'     => 'my\logic\Error::notfound',
    '/403'     => 'my\logic\Error::forbidden'
]);

// create app
$app = new craft\App($router);

// listen 404 event
$app->on(404, function() {
    go('/404');
});

// listen 403 event
$app->on(403, function() {
    go('/403');
});

// go !
$app->handle();