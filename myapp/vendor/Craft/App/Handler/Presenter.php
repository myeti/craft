<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Handler;

use Craft\App\Handler;
use Craft\App\Roadmap;
use Craft\App\View;
use Craft\App\Roadmap\Sketch;

class Presenter extends Handler
{

    /**
     * Get handler name
     * @return string
     */
    public function name()
    {
        return 'presenter';
    }


    /**
     * Handle an give back the env
     * @param Roadmap $context
     * @return Roadmap
     */
    public function handleRoadmap(Roadmap $roadmap)
    {
        // can render ?
        if(!$roadmap->service and !empty($roadmap->draft->metadata['render'])){

            // create view
            $sketch = new Sketch();
            $sketch->template = $roadmap->draft->metadata['render'];
            $sketch->data = is_array($roadmap->draft->data) ? $roadmap->draft->data : [];

            // compile content
            $sketch->content = View::forge($sketch->template, $sketch->data);

            // update env
            $roadmap->sketch = $sketch;

            // display content
            echo $sketch->content;
        }

        return $roadmap;
    }

}