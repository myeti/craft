<?php

/**
 * Hello :)
 */

use Craft\App;
use Craft\App\Plugin;
use Craft\Box\Mog;
use Craft\View;
use Craft\Router;
use Craft\Orm;
use Craft\Debug\Logger;


/**
 * Logger setup
 */

Logger::register(
    new Logger\DailyFile(__DIR__ . '/logs')
);


/**
 * Database setup
 */

Orm\Syn::SQLite(__APP__ . '/craft.db')
    ->map('App\Model\User')          // map entity 'App\Model\User'
    ->build();                       // build your models into your db


/**
 * Generate request
 */

$request = App\Request::create();
Mog::request($request);


/**
 * Router setup
 */

$urls = new Router\Urls([
    '/' => 'App\Front\Home::page'
]);

$routing = new Plugin\Routing($urls);


/**
 * Auth firewall setup
 */

$firewall = new Plugin\Firewall;


/**
 * Json and Html rendering engine
 */

$json = new Plugin\Jsoning(JSON_PRETTY_PRINT);

$html = new Plugin\Templating(
    new View\Html(__APP__ . '/Front/views', $request->url()->relative())
);


/**
 * Final application setup
 */

$app = new App\Kernel($routing, $firewall, $json, $html);


/**
 * Debugging plugin for dev mode
 */

if(__ENV__ == 'dev') {
    $app->plug(new Plugin\Debugging);
}


/**
 * Http errors (404 & 403) handling
 */

$app->on(404, function($request, &$response) use($html) {
    $html->generate('error.404', $response)->code(404);
});

$app->on(403, function($request, &$response) use($html) {
    $html->generate('error.403', $response)->code(403);
});


/**
 * Time to run your app !
 */

$app->handle($request);