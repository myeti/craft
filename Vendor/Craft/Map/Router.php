<?php

namespace Craft\Map;

use Craft\Reflect\Annotation;
use Craft\Storage\Finder\GlobFinder;

class Router implements RouterInterface
{

    /** @var Route[] */
    protected $routes = [];

    /** @var array */
    protected $verbs = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'];


    /**
     * Setup matcher
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        foreach($routes as $from => $to) {
            if($to instanceof Route) {
                $this->add($to);
            }
            else {
                $this->map($from, $to);
            }
        }
    }


    /**
     * Make route from path
     * @param string $from
     * @param mixed $to
     * @param array $customs
     * @return $this
     */
    public function map($from, $to, array $customs = [])
    {
        return $this->add(new Route($from, $to, $customs));
    }


    /**
     * Add route
     * @param Route $route
     * @return $this
     */
    public function add(Route $route)
    {
        $this->routes[$route->from] = $route;
        return $this;
    }


    /**
     * Bind an inner router
     * @param string $base
     * @param RouterInterface $matcher
     * @return $this
     */
    public function bind($base, RouterInterface $matcher)
    {
        foreach($matcher->routes() as $route) {
            $route->from = $base . $route->from;
            $this->add($route);
        }
        return $this;
    }


    /**
     * Get all routes
     * @return Route[]
     */
    public function routes()
    {
        return $this->routes;
    }


    /**
     * Find route
     * @param string $query
     * @param array $customs
     * @param mixed $fallback
     * @return Route
     */
    public function find($query, array $customs = [], $fallback = false)
    {
        // prepare query
        list($query, $customs) = $this->prepare($query, $customs);

        // search in all routes
        foreach($this->routes as $route)
        {
            // route filter
            $route = $this->filter($route);

            // compile pattern
            $pattern = $this->compile($route->from);

            // compare
            if(preg_match($pattern, $query, $data)){

                // check customs
                if(!$this->check($route, $customs)) {
                    continue;
                }

                // parse data
                unset($data[0]);
                $route = $this->parse($route, $data);

                return $route;
            }
        }

        return $fallback;
    }


    /**
     * Find route
     * @param string $query
     * @param array $customs
     * @return string
     */
    protected function prepare($query, array $customs = [])
    {
        // resolve path
        list($verb, $query) = $this->resolve($query);

        // update customs
        if($verb) {
            $customs['method'] = $verb;
        }

        // clean query
        $query = '/' . trim($query, '/');

        return [$query, $customs];
    }


    /**
     * Filter route
     * @param Route $route
     * @return Route
     */
    protected function filter(Route $route)
    {
        // resolve path
        list($verb, $query) = $this->resolve($route->from);

        // update customs
        if($verb) {
            $route->customs['method'] = $verb;
        }

        // clean query
        $route->from =  '/' . trim($query, '/');

        return $route;
    }


    /**
     * Compile path into regex
     * @param $path
     * @return mixed|string
     */
    protected function compile($path)
    {
        $pattern = str_replace('/', '\/', $path);
        $pattern = preg_replace('#\:(\w+)#', '(?P<arg__$1>(.+))', $pattern);
        $pattern = preg_replace('#\+(\w+)#', '(?P<env__$1>(.+))', $pattern);
        $pattern = '#^' . $pattern . '$#';

        return $pattern;
    }


    /**
     * Check customs
     * @param Route $route
     * @param array $customs
     * @return bool
     */
    protected function check(Route $route, array $customs = [])
    {
        return (array_intersect_assoc($customs, $route->customs) == $route->customs);
    }


    /**
     * Parse results
     * @param Route $route
     * @param array $data
     * @return array
     */
    protected function parse(Route $route, array $data)
    {
        // default values
        $parsed = [
            'args' => [],
            'envs' => []
        ];

        // parse
        foreach($data as $key => $value) {
            if(substr($key, 0, 5) == 'arg__' or substr($key, 0, 5) == 'env__') {
                $group = substr($key, 0, 3) . 's';
                $label = substr($key, 5);
                $parsed[$group][$label] = $value;
            }
        }

        // update route
        $route->data = $parsed['args'];
        $route->meta = $parsed['args'];

        return $route;
    }


    /**
     * Resolve query
     * @param $query
     * @return array
     */
    protected function resolve($query)
    {
        // has http verb
        if($query and strpos(' ', $query) !== false) {

            // get verb
            list($verb, $path) = explode(' ', $query);
            $verb = strtoupper($verb);

            // return resolved
            if(in_array($verb, $this->verbs)) {
                return [$verb, $path];
            }

        }

        return [null, $query];
    }


    /**
     * Setup routes from methods
     * @param array $classes
     * @throws \InvalidArgumentException
     * @return Router
     */
    public static function actions(array $classes)
    {
        $routes = [];

        // for all classes
        foreach($classes as $class) {

            // not class
            if(!class_exists($class)) {
                throw new \InvalidArgumentException('Class "' . $class . '" not found.');
            }

            // scan class
            $ref = new \ReflectionClass($class);

            // make query
            $query = '/' . strtolower($ref->getShortName());

            // get methods
            foreach($ref->getMethods() as $method) {

                // add action in query
                if($method->getName() != 'index') {
                    $query .= '/' . strtolower($method->getName());
                }

                // front::index as root
                if($query == '/front') {
                    $query = '/';
                }

                // args
                $args = $method->getParameters();
                foreach($args as $arg) {

                    // optional param : save last route
                    if($arg->isOptional()) {
                        $routes[$query] = [$class, $method];
                    }

                    // add to query
                    $query .= '/:' . strtolower($arg->getName());
                }

                // save (last) route
                $routes[$query] = [$class, $method];
            }

        }

        return new self($routes);
    }


    /**
     * Setup routes from annotations
     * @param array $classes
     * @throws \InvalidArgumentException
     * @return Router
     */
    public static function annotations(array $classes)
    {
        $routes = [];

        // for all classes
        foreach($classes as $class) {

            // not class
            if(!class_exists($class)) {
                throw new \InvalidArgumentException('Class "' . $class . '" not found.');
            }

            // and all methods
            foreach(get_class_methods($class) as $method) {

                // @route specified
                if($url = Annotation::method($class, $method, 'route')) {
                    $routes[$url] = [$class, $method];
                }

            }

        }

        return new self($routes);
    }


    /**
     * Setup routes from files
     * @param string $dir
     * @throws \InvalidArgumentException
     * @return Router
     */
    public static function files($dir)
    {
        // get all files and sub files
        $files = new GlobFinder($dir, '*');

        // null action
        $null = function(){};

        // make routes
        $routes = [];
        foreach($files as $file) {

            // clean path
            $path = str_replace($dir, null, pathinfo($file, PATHINFO_BASENAME));
            $path = '/' . ltrim($path, '/');
            $template = str_replace($dir, null, $file);

            // index route
            if($path == '/index') {
                $path = '/';
            }

            // make route
            $route = new Route($path, $null);
            $route->meta['render'] = $template;
            $routes[] = $route;
        }

        return new self($routes);
    }

} 