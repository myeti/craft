# Craft 2.2

Craft is a small & efficient PHP5.4+ framework that helps you to quickly build webapps.
It only provides mere tools and libs, the rest is up to your creativity !


## Quickstart

All start in your 'index.php' :

```php
<?php

require 'vendor/autoload.php';

$app = new Forge\App([
    '/'         => 'My\Logic\Front::hello', // landing page
    '/lost'     => 'My\Logic\Error::lost',  // 404 route
    '/nope'     => 'My\Logic\Error::nope'   // 403 route
]);

$app->handle();
```

You can add params to your url : '/url/with/:param', your method will receive `$param`.


## Database

You can use `Syn`, the embeded orm, and map your own models :

```php
<?php

use Forge\Syn;

Syn::MySQL('dbname')        // or Syn::SQLite(dbfile)
    ->map('My\Entity\User')
    ->build();              // deploy structure

$users = User::all();
$users = User::all(['id =' => 10]); // or
$users = User::get()->where('age =', 10)->all();

$user = User::one(['id =' => 10]); // or
$user = new My\Entity\User;
$user->age = 15;

User::save($user);

User::get()->where('id =', 2)->drop();
```

## Session & Auth

Let's manage your users, shall we ?

```php
<?php

// tell the auth object the model you want to use
Forge\Auth::seek('My\Entity\User');

// attempt a login
if(Forge\Auth::attempt($username, $password)) {
    // logged in
}
else {
    // failed
}

// or manually login the user
Forge\Auth::login($rank, $user); // rank number, user object

// get rank
$rank = Forge\Auth::rank();

```

You want to keep some data in memory ?

```php
<?php

// write
Forge\Session::set('foo', 'bar');

// read
$foo = Forge\Session::get('foo');

// clear
Forge\Session::drop('foo');
```

### Simple, isn't it ?