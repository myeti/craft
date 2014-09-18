<?php

/**
 * Hello !
 * I'll help you build your app :)
 */
require 'vendor/autoload.php';


/**
 * Then, you might need to setup you database.
 * Here is how to use a SQLite local base :
 */

use Craft\Orm;

Orm\Syn::SQLite(__APP__ . '/craft.db')     // or Syn::MySQL('dbname', [host, username, password])
         ->map('My\Entity\User')           // map entity 'My\Model\User'
         ->build();                        // build your models into your db


/**
 * You can now create your routes.
 * You can define params /url/with/:id, then the action will receive $id
 * Or you can define env config /+lang/url, then you can retrieve with env('lang')
 */

use Craft\Routing;

$router = new Routing\UrlRouter([
    '/'     => 'My\Logic\Front::hello',
    '/lost' => 'My\Logic\Front::lost',
    '/nope' => 'My\Logic\Front::nope'
]);


/**
 * Define your template engine that will render
 * your awesome design !
 */

use Craft\View;

$engine = new View\Engine(__APP__ . '/views');


/**
 * Create your application using these components
 */

use Craft\App;

$app = new App\Bundle($router, $engine);


/**
 * Tell the app how to handle http errors (404 & 403)
 */

$app->on(404, App\Response\Event::redirect('/lost'));
$app->on(403, App\Response\Event::redirect('/nope'));


/**
 * You can now run your webapp,
 * well done !
 */
$app->handle();