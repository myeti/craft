<?php

/**
 * Hello !
 * I'll help you building your app :)
 */
require 'vendor/autoload.php';


/**
 * First, you might need to setup you database.
 * Here is how to use a MySQL local base :
 */

use Craft\Orm\Syn;

Syn::SQLite('craft.db')      // open dbname on localhost
    ->map('My\Entity\User')  // map entity 'My\Model\User'
    ->build();               // build your models into your db


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

$app = new Forge\App([
    '/'         => 'My\Logic\Front::hello',
    '/oops'     => 'My\Logic\Error::oops',
    '/nope'     => 'My\Logic\Error::nope'
]);

/**
 * You can define params ':' and env '+'
 * - params will passed to action as arguments
 * - env will be available with Craft\Box\Env::get('yourenv');
 *//*

$app = new Forge\App([
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
$app->plug(new Forge\Routing([
    '/'         => 'My\Logic\Front::hello',
    '/oops'     => 'My\Logic\Error::oops',
    '/nope'     => 'My\Logic\Error::nope'
]));

// you want to read action metatag ? (@auth, @render...)
$app->plug(new Forge\Metadata);

// you need to check user auth ?
$app->plug(new Forge\Firewall);

// finally, you want to render your templates using the native engine ?
$app->plug(new Forge\Html);

/**
 * If you want to create your own plugin, just extend the Craft\App\Layer class
 * and override the method you want : before() and/or after() and/or finish()
 * - before($request) will change the current request before execution
 * - after($request, $response) will change the response before rendering
 * - finish($request, $response) won't change anything, but can be useful (cache, stats)
 *
 * Check the Craft\App\Layer folder for some example.
 */


/**
 * Sometimes, something wrong happen
 * and you might need to use these error.
 * Just like the page not found (404)
 * and user forbidden (403) :
 */
$app->oops('/oops'); // 404 : redirect to '/oops'
$app->nope('/nope'); // 403 : redirect to '/nope'


/**
 * You can now run your webapp,
 * well done !
 */
$app->handle();