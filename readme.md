# Craft 2.5

Craft is a small & efficient PHP5.6 framework that helps you to quickly build webapps.
It only provides mere tools and libs, the rest is up to your creativity !


## Quickstart

Everything starts in your `app/dev.php` :

```php
<?php

use Craft\Box;
use Craft\Web;
use Craft\App;
use Craft\View;
use Craft\Router;

// generate the request
$request = App\Request::create();

// create your routes
$router = new Router\Urls([
    '/' => 'App\Logic\Front::hello',
]);

// define your template engine
$html = new View\Engine\Html(__APP__ . '/views');

// forge your app with these components
$app = new Web\App($router, $html);

// let's go !
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

#### Simple, isn't it ?