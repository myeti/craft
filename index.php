<?php

# Hello :)
require 'vendor/autoload.php';

use Craft\Orm\Syn;
use Craft\Web\App;

# Open your db & map your models
# Syn::MySQL('mydbname')
#     ->map('user', 'My\Model\User')
#     ->build();

# Setup your business routes
$app = new App([
    '/'         => 'My\Logic\Front::hello',
    '/lost'     => 'My\Logic\Error::lost',
    '/sorry'    => 'My\Logic\Error::sorry'
]);

# Handle 404 & 403
$app->on(404, function() use($app) {
    $app->to('/lost');
})->on(403, function() use($app) {
    $app->to('/sorry');
});

# Let's go !
$app->handle();