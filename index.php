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

Forge\Syn::SQLite('craft.db')      // or Syn::MySQL('dbname', [host, username, password])
          ->map('My\Entity\User')  // map entity 'My\Model\User'
          ->build();               // build your models into your db


/**
 * Tell the Auth object how to retrieve
 * your user from login attempt.
 */

Forge\Auth::seek('My\Entity\User');


/**
 * You can now create your app with the routes.
 * You can define params /url/with/:id, then the action will receive $id
 * Or you can define env config /+lang/url, then you can retrieve with Forge\Env::get('lang')
 */

$app = new Forge\App([
    '/'          => 'My\Logic\Front::hello',
    '/lost'      => 'My\Logic\Error::lost',
    '/nope'      => 'My\Logic\Error::nope'
]);


/**
 * Sometimes, something wrong happen
 * and you might need to use these error.
 */
$app->lost('/lost'); // 404 : redirect to '/lost'
$app->nope('/nope'); // 403 : redirect to '/nope'


/**
 * You can now run your webapp,
 * well done !
 */
$app->handle();