<?php

require '../craft/toolkit.php';

$router = new craft\Router([
    '/'      => 'my\logic\Front::index',
    '/about' => 'my\logic\Front::about'
]);

$app = new craft\App($router);
$app->handle();