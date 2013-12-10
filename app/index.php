<?php

require '../craft/bundle.php';

// create app
$app = new craft\App([
    '/'         => 'my\logic\Front::hello',
    '/lost'     => 'my\logic\Error::lost',
    '/sorry'    => 'my\logic\Error::sorry'
]);

// listen 404 event
$app->on(404, function() {
    go('/lost');
});

// listen 403 event
$app->on(403, function() {
    go('/sorry');
});

// plug & wait
$app->plug();