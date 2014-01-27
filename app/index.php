<?php

/**
 * Hello, I'm your `index.php`, your entry point,
 * everything starts with me !
 *
 * First, you need to load the craft bundle allowing you
 * to use libraries and components.
 */
require 'vendor/craft/bundle.php';


/**
 * If you need to use third-party libraries, then add them to the class loader.
 * As an advise, you should put all your libraries under the `vendor` directory.
 */
craft\Loader::vendor('lib', 'vendor/MyLib');


/**
 * Do you use a database ?
 *
 * In that case, craft provide you with with a well-thought
 * simple and easy-to-use orm : Syn.
 *
 * You can setup MySQL, SQLite, or your own mapper (@see Syn::mapper()).
 *
 * If you don't setup Syn, it will provide fake data based on Lipsum.
 */
craft\orm\Syn::mysql('my_db', [
    'host'   => '127.0.0.1',
    'user'   => 'root',
    'pass'   => null,
    'prefix' => null
]);


/**
 * More than a database, you have models ?
 *
 * Good, then tell Syn tu use them and don't forget to
 * add the `@var` metadata to each property of the model
 * (@see my\models\User)
 *
 * And if you want Syn to build your database from your models,
 * just ask the `merge()` method !
 */
craft\orm\Syn::map([
    'user'  => 'my\models\User'
]);

craft\orm\Syn::merge();


/**
 * It's time to setup your routes !
 *
 * Simple : for each URL, define an action,
 * like : '/my/url' => 'my\Action::method'.
 *
 * You need to get some parameters from the url ?
 * No problem : '/my/url/with/:param' => 'my\Action::method'.
 * The `:param` will be given to the method as `public function method($param)`
 *
 * To make it well organized, you should put all your actions
 * under one namespace (why not my\logic ?)
 *
 * @see `my\logic\Front` to know how to build a controller.
 */
$app = new craft\web\App([
    '/'         => 'my\logic\Front::hello',
    '/lost'     => 'my\logic\Error::lost',
    '/sorry'    => 'my\logic\Error::sorry'
]);


/**
 * An usual webapp might return 2 errors :
 * - page/action not found (404)
 * - page/action not allowed (403)
 *
 * In order to take control of these errors,
 * just intercept them and replug your app
 * on a specified url.
 */
$app->on(404, function() use($app) {
    $app->plug('/lost');
});

$app->on(403, function() use($app) {
    $app->plug('/sorry');
});


/**
 * Well, everything is ready, it's time to plug
 * your app on the current url.
 *
 * Thanks for using Craft ;)
 */
$app->plug();