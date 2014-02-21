# Craft\Box

This package contains tools to interact with session, cookie, flash, authentication and environment data.


## Session

This class is binded to the `$_SESSION` global data.

Store data :

```php
Craft\Box\Session::set('foo', 'bar');
```

Check if data exists :

```php
Craft\Box\Session::has('foo');
```

Retrieve data :

```php
$foo = Craft\Box\Session::get('foo');

# or with fallback
$foo = Craft\Box\Session::get('foo', 'valueIfNotExists');
```

Delete data :

```php
Craft\Box\Session::drop('foo');
```


## Flash

Works the same way as `Session` except the `get()` method that reads **and** drops the content.

```php
$message = flash('form.success'); // alias
```


## Cookie

Binded to `$_COOKIE`, works the same way as `Session` (but not Boxent).


## Config

Binded to `$_ENV`, works the same way as `Session`.


## Auth

Use the session but has a different behavior :

Log a user in :

```php
$rank = 5;
$user = 'Babor';

Craft\Box\Auth::login($rank, $user);
```

Log a user out :

```php
Craft\Box\Auth::logout();
```

Check if user is logged in :

```php
if(Craft\Box\Auth::logged()) {

}
```

Check rank (must be logged in) :

```php
$test = 8;
Craft\Box\Auth::allowed($test); // false
```

Get auth data :

```php
$rank = Craft\Box\Auth::rank();
$user = Craft\Box\Auth::user();
```


## Mog

Mog is your best friend, yes really !
It contains all the data you need about $_SERVER, $_POST and $_GET :

POST data :

```php
$all = Craft\Box\Mog::post();
$one = Craft\Box\Mog::post('one');
$one = post('one'); // alias
```

SERVER and GET works the same way as POST.

Tools :

```php
use Craft\Box\Mog;

$ip = Mog::ip();
$referer = Mog::server('HTTP_REFERER');
$lastUrl = Mog::from();
$ajax = Mog::async();
$browser = Mog::browser();
$mobile = Mog::mobile();
```

And a lot more, check the code ;)


## Extend the session

If you want to run your own session, these classes use the following object to bind data :

```php
$session = new Craft\Box\SessionRepository('my.key');
```