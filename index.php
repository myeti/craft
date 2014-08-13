<?php

/**
 * Hello !
 * I'll help you building your app :)
 */
require 'vendor/autoload.php';


/**
 * First, you might need to setup you database.
 * Here is how to use a SQlite local base :
 */

Forge\Syn::SQLite(__APP__ . '/craft.db')    // or Syn::MySQL('dbname', [host, username, password])
          ->map('My\Entity\User')           // map entity 'My\Model\User'
          ->build();                        // build your models into your db


/**
 * You can now create your routes.
 * You can define params /url/with/:id, then the action will receive $id
 * Or you can define env config /+lang/url, then you can retrieve with Forge\Mog::env('lang')
 */

$routes = [
    '/'     => 'My\Logic\Front::hello',
    '/lost' => 'My\Logic\Error::lost',
    '/nope' => 'My\Logic\Error::nope'
];


/**
 * Setup your application using routes and templates directory
 * and tell the app how to handle errors like 404 and 403
 */
$app = new Forge\App($routes, __APP__ . '/views');
$app->lost('/lost'); // 404 : redirect to '/lost'
$app->nope('/nope'); // 403 : redirect to '/nope'


/**
 * You can now run your webapp,
 * well done !
 */
$app->handle();