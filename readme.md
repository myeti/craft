# Craft 2.5.1

Craft is a small & efficient PHP5.6 framework that helps you to quickly build webapps.
It only provides mere tools and libs, the rest is up to your creativity !


## Quickstart

Everything starts in your `app.php` :

```php
<?php

use Craft\App;
use Craft\App\Plugin;
use Craft\View;
use Craft\Router;


/**
 * Generate the request
 */
$request = App\Request::create();


/**
 * Define your routes
 */
$urls = new Router\Urls([
    '/' => 'App\Front\Home::page'
]);

$routing = new Plugin\Routing($urls);


/**
 * Setup your html engine
 */
$templating = new Plugin\Templating(
    new View\Html(__APP__ . '/Front/views', $request->url()->relative())
);

/**
 * Forge your app with these components
 */
$app = new App\Kernel($routing, $templating);
$app->handle($request);
```

You can add params to your url : `/url/:with/:param`, your method will receive `$with` and `$param`.
For example, the rule `/user/:id` will catch `/user/18` and the method will receive `$id = 18`.


## Database

You can use `Syn`, the inner orm, and map your own models :

```php
<?php

use Craft\Orm\Syn;

Syn::MySQL('dbname')        // or Syn::SQLite(dbfile)
    ->map('App\Entity\User')
    ->build();              // deploy structure

$users = User::find();
$users = User::find(['id =' => 10]); // or
$users = User::query()->read()->where('age =', 10)->find();

$user = User::one(['id =' => 10]); // or
$user = new App\Entity\User;
$user->age = 15;

User::save($user);

User::query()->drop()->where('id =', 2)->apply();
```

## Session & Auth

Let's manage your users, shall we ?

```php
<?php

use Craft\Box\Auth;

// attempt a basic login
if(Auth::basic($username, $password)) {
    // logged in
}
else {
    // failed
}

// or manually login the user
Auth::login($rank, $user); // rank number, user object

// get rank or user
$rank = Auth::rank();
$user = Auth::user();

```

You want to keep some data in memory ?

```php
<?php

use Craft\Box\Session;

// write
Session::set('foo', 'bar');

// read
$foo = Session::get('foo');

// clear
Session::drop('foo');
```

## OVH

Special configuration for OVH hosting

public/.htaccess :
```
<IfModule mod_rewrite.c>
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?$1 [L]
</IfModule>
```

app/dev|prod.php, replace :
```php
$request = App\Request::create();
Mog::request($request);
```
with :
```php
$request = App\Request::create(Craft\Http\Url::QUERY_STRING);
Mog::request($request);
```

#### Simple, isn't it ?