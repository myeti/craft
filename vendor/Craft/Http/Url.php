<?php

namespace Craft\Http;

class Url
{

    const PATH_INFO = 1;
    const QUERY_STRING = 2;

    /** @var string */
    public $scheme;

    /** @var string */
    public $host;

    /** @var string */
    public $base;

    /** @var string */
    public $query = '/';

    /** @var array */
    public $params = [];

    /** @var string */
    public $hash;


    /**
     * Parse and init
     * @param null $url
     * @param null $base
     */
    public function __construct($url = null, $base = null)
    {
        $this->base = (string)$base;
        if($url) {
            $parsed = parse_url((string)$url);
            if(isset($parsed['scheme'])) {
                $this->scheme = $parsed['scheme'];
            }
            if(isset($parsed['host'])) {
                $this->host = $parsed['host'];
            }
            if(isset($parsed['path'])) {
                $this->query = substr($parsed['path'], strlen($this->base));
            }
            if(isset($parsed['fragment'])) {
                $this->hash = $parsed['fragment'];
            }
            if(isset($parsed['query'])) {
                parse_str($parsed['query'], $this->params);
            }
        }
    }


    /**
     * Compare query
     * @param string $query
     * @return bool
     */
    public function is($query)
    {
        // build regex
        $regex = preg_quote($query);
        $regex = str_replace('\*', '(.*)', $regex);
        $regex = '#' . $regex . '#';

        return preg_match($regex, $this->full());
    }


    /**
     * Make absolute url
     * @param string $query
     * @param array $params
     * @return string
     */
    public function absolute($query = null, array $params = [])
    {
        $url = '';

        if($this->host) {
            $url = rtrim($this->host, '/');
            if($this->scheme) {
                $url = $this->scheme . '://' . $url;
            }
        }

        if($relative = $this->relative($query, $params)) {
            $url .= $relative;
        }

        return $url;
    }


    /**
     * Make relative url
     * @param string $query
     * @param array $params
     * @return string
     */
    public function relative($query = null, array $params = [])
    {
        $url = '/';

        $base = trim($this->base, '/');
        if($base) {
            $url .= $base . '/';
        }

        $query = ltrim($query, '/');
        if($query) {
            $url .= $query;
        }

        $url = rtrim($url, '/');

        $params = http_build_query($params);
        if($params) {
            $url .= '?' . $params;
        }

        return $url;
    }


    /**
     * Get full url
     * @param bool $absolute
     * @return string
     */
    public function full($absolute = false)
    {
        return $absolute
            ? $this->absolute($this->query, $this->params)
            : $this->relative($this->query, $this->params);
    }


    /**
     * Return full url
     * @return string
     */
    public function __toString()
    {
        return $this->full();
    }


    /**
     * Generate url from $_SERVER
     * @param int $strategy
     * @return static
     */
    public static function current($strategy = self::PATH_INFO)
    {
        // default
        $url = '/';
        $base = null;

        // common
        if(isset($_SERVER['HTTP_HOST'])) {
            $url = $_SERVER['HTTP_HOST'] . $url;
            if(isset($_SERVER['REQUEST_SCHEME'])) {
                $url = $_SERVER['REQUEST_SCHEME'] . '://' . $url;
            }
        }

        // query
        $query = null;
        if(isset($_SERVER['REQUEST_URI'])) {
            $query = ltrim($_SERVER['REQUEST_URI'], '/');
        }
        $url .= $query;

        // clean query
        $query = explode('?', $query)[0];
        $base = $query;

        // resolve base from query_string : index.php?query/string
        if($strategy == self::QUERY_STRING and !empty($_SERVER['QUERY_STRING'])) {
            $query_string = ltrim($_SERVER['QUERY_STRING'], '/');
            $base = substr($query, 0, -strlen($query_string));
            unset($_GET[$_SERVER['QUERY_STRING']]);
        }
        // resolve base from path_info : index.php/path/info
        elseif(!empty($_SERVER['PATH_INFO'])) {
            $path_info = ltrim($_SERVER['PATH_INFO'], '/');
            $base = substr($query, 0, -strlen($path_info));
        }

        return new static($url, $base);
    }

} 