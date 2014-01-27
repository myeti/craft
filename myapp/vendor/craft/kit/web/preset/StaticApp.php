<?php

namespace craft\kit\web\preset;

use craft\kit\web\App;
use craft\kit\web\NeedContext;
use craft\kit\dispatcher\DispatchException;

class StaticApp extends App
{

    use NeedContext;

    /** @var string */
    protected $_dir;


    /**
     * Setup static web
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
            $this->plug('/404');
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

        // inject render metadata
        if(file_exists($page)) {
            $this->context->action->metadata['render'] = $page;
        }
        else {
            throw new DispatchException(404, 'Template "' . $page . '" does not exist.');
        }
    }

} 