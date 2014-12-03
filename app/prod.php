<?php

/************************************
 * Production mode
 ************************************/

use Craft\Debug\Logger;
use Craft\Box\Mog;
use Craft\Orm;
use Craft\Router;
use Craft\View;
use Craft\App;
use Craft\Web;


/**
 * Error level
 */

error_reporting(0);
ini_set('error_reporting', 0);


/**
 * Generate request
 */

$request = App\Request::create();
Mog::request($request);


/**
 * Logger setup
 */

Logger::register(
    new Logger\DailyFile(__APP__ . '/logs')
);


/**
 * Database setup
 */

Orm\Syn::SQLite(__APP__ . '/craft.db')
    ->map('App\Entity\User')          // map entity 'App\Model\User'
    ->build();                        // build your models into your db


/**
 * Router setup
 * You can define params /url/with/:id, then the action will receive $id
 */

$router = new Router\Urls([
    '/'     => 'App\Logic\Front::hello'
]);


/**
 * Template engine setup
 */

$html = new View\Engine\Html(
    __APP__ . '/views',
    $request->url()->relative()
);


/**
 * Final application setup
 */

$web      = new App\Plugin\Web($router, $html);
$firewall = new App\Plugin\Firewall;

$app = new App\Kernel($web, $firewall);


/**
 * Http errors (404 & 403) handling
 */

$app->on(404, function($request, &$response) use($html) {
    $template = $html->render('error.404');
    $response = new App\Response($template, 404);
});

$app->on(403, function($request, &$response) use($html) {
    $template = $html->render('error.403');
    $response = new App\Response($template, 403);
});


/**
 * Run ! You fools !
 */

$app->handle($request);