<?php

namespace craft\preset\app;

use craft\kit\webapp\Context;
use craft\kit\webapp\App;
use craft\kit\webapp\DispatchException;

class StaticApp extends App
{

    /** @var string */
    protected $_dir;

    /**
     * Setup static app
     * @param array $dir
     */
    public function __construct($dir)
    {
        // set views directory
        $this->_dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        // routing
        $routes = [
            '/'     => [$this, 'page'],
            '/:id'  => [$this, 'page']
        ];

        // init dispatcher
        parent::__construct($routes);

        // 404
        $this->on(404, function(){
            $this->dispatch('/404');
        });
    }

    /**
     * Render static page
     * @param null|string $page
     * @throws DispatchException
     */
    public function page($page = 'index')
    {
        // make path
        $page = $this->_dir . $page . '.php';

        // check if view exists in dir
        if(file_exists($page)) {

            // update env env
            Context::get()->build->metadata['render'] = $page;

        }
        else {
            throw new DispatchException(404, 'Template "' . $page . '" does not exist.');
        }
    }

} 