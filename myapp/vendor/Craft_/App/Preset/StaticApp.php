<?php

namespace Craft\App\Preset;

use Craft\App;
use Craft\App\Roadmap;
use Craft\Box\Error\SomethingIsWrongException;

class StaticApp extends App
{

    /** @var string */
    protected $dir;


    /**
     * Setup static web
     * @param array $dir
     */
    public function __construct($dir)
    {
        // set views directory
        $this->dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

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
     * @param string $page
     * @param Roadmap $roadmap
     * @throws SomethingIsWrongException
     */
    public function page($page = 'index', Roadmap $roadmap)
    {
        // make path
        $page = $this->dir . $page . '.php';

        // inject render metadata
        if(file_exists($page)) {
            $roadmap->sketch->template = $page;
        }
        else {
            throw new SomethingIsWrongException('Template "' . $page . '" does not exist.', 404);
        }
    }

} 