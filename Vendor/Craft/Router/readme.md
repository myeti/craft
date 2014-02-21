# Craft\Router

A router is a component that resolve a path between a query and its target using specific rules.

```
          rules
            |
query -> [router] -> route
```


### WebRouter

In this example, we'll use the WebRouter (also used by the Craft\Web component).
First, create your routes :

```php
$router = new Craft\Router\Web([
    '/'    => 'Front::index',
    '/foo' => 'Front::foo',
    '/bar' => 'Front::bar'
]);
```

The routes can either be a callable string (url => callable) or a Craft\Router\Route object.

You can also setup routes afterward :

```php
$router->map('/foo', 'Front::foo');
```

or

```php
$router->add(new Route('/foo', 'Front::foo'));
```


### Specify method

In some case, you migth need to filter the request depending on the method.
Here is how you can do it :

```php
$router->map('POST /foo', 'Front::foo');
```

If no method is specified, there won't be any filtering.


### Arguments

You can catch 2 types of arguments : parameters (:) and environment data (+) :

```php
$router->map('/+lang/foo/:id', 'Front::foo');
```

These arguments can be retrieved from the Route `data` properties.


### Route this please !

It's now time to run the router :

```php
$route = $router->find('/fr/foo/5');

$route->to; // Front::foo
$route->data['envs']['lang']; // fr
$route->data['args']['id']; // 5
```

If no routes are found, `false` is returned.


### Create your own router

You can create a router by extending the Matcher class and implementing the `find` method.
The WebRouter is only an example, router can be used for any kind of request :)


### And after ?

You are now able to find a route depending on the query.
You can use the `Craft\Web` component to execute action stored in the route !

