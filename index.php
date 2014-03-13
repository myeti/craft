<?php

# Hello :)
require 'vendor/autoload.php';

# Start tracking
$tracker = new Craft\Trace\Tracker();
$tracker->monitor('app');

# Routing
$app = Craft\Web\App::forge([
    '/'         => 'My\Logic\Front::hello',
    '/lost'     => 'My\Logic\Error::lost',
    '/sorry'    => 'My\Logic\Error::sorry'
]);

# Not found
$app->on(404, function() use($app) {
    $app->plug('/lost');
});

# Forbidden
$app->on(403, function() use($app) {
    $app->plug('/sorry');
});

# Handle query
$app->plug();

# Need report ?
echo $tracker->end('app');