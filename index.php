<?php

/**
 * Hello !
 * I'll help you building your app :)
 */
require 'vendor/autoload.php';

/**
 * During development, tracking time and memory
 * is a useful tool enhancing productivity
 */

use Craft\Trace\Tracker;

$tracker = new Tracker();
$tracker->start('app');


/**
 * First, you might need to setup you database.
 * Here is how to use a MySQL local base :
 *//*

use Craft\Orm\Syn;

Syn::MySQL('dbname')              // open dbname on localhost
    ->map('user', 'My\Model\User')  // map entity 'user' as 'My\Model\User'
    ->build();                      // build your models into your db

/**
 * You can manually change the db settings :
 *//*

Syn::MySQL('dbname', [
    'host' => 'some ip address',
    'username' => 'foo',
    'password' => 'bar',
    'prefix'   => 'my_'
]);


/**
 * You can now create your app.
 * In order to make simple to use
 * you can directly use the Bundle package :
 * it's a ready to use, already setup app.
 *
 * You just have to set the routes to your actions :
 */

$app = new Craft\App\Bundle([
    '/'         => 'My\Logic\Front::hello',
    '/lost'     => 'My\Logic\Error::lost',
    '/sorry'    => 'My\Logic\Error::sorry'
]);

/**
 * You can define params ':' and env '+'
 * - params will passed to action as arguments
 * - env will be available with Craft\Box\Env::get('yourenv');
 *//*

$app = new Craft\App\Bundle([
    '/foo/:bar'   => 'My\Foo::bar',     // class Foo, function bar($bar)
    '/+lang/home' => 'My\Foo::home',    // $lang = Craft\Box\Env::get('lang');
]);

/**
 * Or you can manually build your app using
 * many layer and plugins, you'll see : it's cool :D
 *//*

// it's the simplest form, it just execute action
$app = new Craft\App\Kernel;

// you need a router ?
$app->plug(new Craft\App\Plugin\Router([
    '/'         => 'My\Logic\Front::hello',
    '/lost'     => 'My\Logic\Error::lost',
    '/sorry'    => 'My\Logic\Error::sorry'
]));

// you want to read action metatag ? (@auth, @render...)
$app->plug(new Craft\App\Plugin\Metadata);

// you need to check user auth ?
$app->plug(new Craft\App\Plugin\Firewall);

// finally, you want to render your templates using php engine ?
$app->plug(new Craft\App\Plugin\Templates);

/**
 * If you want to create your own plugin, just extend the Craft\App\Plugin class
 * and override the method you want : before() and/or after() and/or finish()
 * - before($request) will change the current request before execution
 * - after($request, $response) will change the response before rendering
 * - finish($request, $response) won't change anything, but can be useful (cache, stats)
 *
 * Check the Craft\App\Plugin folder for some example.
 */


/**
 * Sometimes, something wrong happen
 * and you might need to use these error.
 * Just like the page not found (404)
 * and user forbidden (403) :
 */
$app->on(404, function() use($app) {
    $app->to('/lost'); // redirect to '/lost'
});

$app->on(403, function() use($app) {
    $app->to('/sorry'); // redirect to '/sorry'
});


/**
 * You can now run your webapp,
 * well done !
 */
$app->handle();


/**
 * Then, get the tracker data to
 * see elapsed time and memory.
 * But you know, Craft is fast ;)
 */

echo $tracker->end('app')->report();