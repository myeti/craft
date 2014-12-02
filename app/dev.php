<?php

/************************************
 * Development mode
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

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);


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
 * You can define params in url like /url/with/:id,
 * then the action will receive $id
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
 * Build services and application
 */

$firewall = new Web\Service\Firewall;
$debugger = new Web\Service\Debugger;

$app = new Web\App($router, $html, $firewall, $debugger);


/**
 * Run ! You fools !
 */

$app->handle($request);