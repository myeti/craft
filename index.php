<?php

# Hello :)
require 'vendor/autoload.php';

# Start tracking
$tracker = new Craft\Trace\Tracker();
$tracker->monitor('app');

# Routing
$app = new Craft\Web\App([
    '/'         => 'My\Logic\Front::hello',
    '/lost'     => 'My\Logic\Error::lost',
    '/sorry'    => 'My\Logic\Error::sorry'
]);

# Catch 404
$app->on(404, function() use($app) {
    $app->plug('/lost');
});

# Catch 403
$app->on(403, function() use($app) {
    $app->plug('/sorry');
});

# Track all events
$app->on('*', function($name) use($tracker) {
    $tracker->info($name);
});

# Handle query
$app->plug();

# Tracking report
$tracker->over('app');
echo $tracker;