<?php

/**
 * Hello !
 * I'll help you build your app :)
 */
require 'vendor/autoload.php';

use Craft\App;
use Craft\Orm;
use Craft\View;
use Craft\Routing;


/**
 * Then, you might need to setup you database.
 * Here is how to use a SQLite local base :
 */

Orm\Syn::SQLite(__APP__ . '/craft.db')     // or Syn::MySQL('dbname', [host, username, password])
         ->map('My\Entity\User')           // map entity 'My\Model\User'
         ->build();                        // build your models into your db


/**
 * You can now create your routes.
 * You can define params /url/with/:id, then the action will receive $id
 * Or you can define env config /+lang/url, then you can retrieve with env('lang')
 */

$router = new Routing\UrlRouter([
    '/'     => 'My\Logic\Front::hello',
    '/lost' => 'My\Logic\Front::lost',
    '/nope' => 'My\Logic\Front::nope'
]);


/**
 * Define your template engine that will render
 * your awesome design !
 */

$engine = new View\Engine(__APP__ . '/views');


/**
 * Create your application using these components
 */

$app = new App\Bundle($router, $engine);


/**
 * Tell the app how to handle http errors (404 & 403)
 */

$app->on(404, App\Event::redirect('/lost'));
$app->on(403, App\Event::redirect('/nope'));


/**
 * You can now run your webapp,
 * well done !
 */
$app->handle();