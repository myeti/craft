<?php

namespace Craft\Steam;

use Craft\App\Request;
use Craft\Box\Mog;

class Zeppelin extends Aeroship
{

    /**
     * Setup zeppelin
     * @param array $roads
     */
    public function __construct(array $roads = [])
    {
        // setup airship
        parent::__construct();

        // defaults routes
        $roads = $roads + [
            '/'         => $this->generate('home'),
            '/lost'     => $this->generate('lost'),
            '/sorry'    => $this->generate('sorry'),
        ];

        // add gears
        $this->engine->plug(new Gear\Cartographer($roads));
        $this->engine->plug(new Gear\Technician);
        $this->engine->plug(new Gear\Officer);
        $this->engine->plug(new Gear\Architect);

        // get ref
        $engine = $this->engine;

        // handle 404
        $this->engine->on(404, function() use($engine) {
            $engine->to('/lost');
        });

        // handle 403
        $this->engine->on(403, function() use($engine) {
            $engine->to('/sorry');
        });
    }


    /**
     * Generate action
     * @return callable
     */
    protected function generate($template)
    {
        return function(Request $request) use($template)
        {
            $path = str_replace(Mog::path(), '', __DIR__ . '/templates/' . $template);
            $request->meta['render'] = $path;
        };
    }

}