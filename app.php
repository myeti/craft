<?php

/**
 * Hello !
 * I'll help you build your app :)
 */
require 'vendor/autoload.php';


/**
 * First, tell the mog your application state
 * - dev, test or production -
 */
Forge\Mog::set('dev');


/**
 * Then, you might need to setup you database.
 * Here is how to use a SQLite local base :
 */

Forge\Syn::SQLite(__APP__ . '/craft.db')    // or Syn::MySQL('dbname', [host, username, password])
          ->map('My\Entity\User')           // map entity 'My\Model\User'
          ->build();                        // build your models into your db


/**
 * You can now create your routes.
 * You can define params /url/with/:id, then the action will receive $id
 * Or you can define env config /+lang/url, then you can retrieve with env('lang')
 */

$router = new Forge\Router([
    '/'     => 'My\Logic\Front::hello',
    '/lost' => 'My\Logic\Front::lost',
    '/nope' => 'My\Logic\Front::nope'
]);


/**
 * Define your template engine that will render
 * your awesome design !
 */

$engine = new Forge\Engine(__APP__ . '/views');


/**
 * Create your application using these components
 */

$app = new Forge\App($router, $engine);


/**
 * Tell the app how to handle http errors (404 & 403)
 */

use Craft\App\Response\Redirect;

$app->on(404, new Redirect('/lost'));
$app->on(403, new Redirect('/nope'));


/**
 * You can now run your webapp,
 * well done !
 */
$app->handle();