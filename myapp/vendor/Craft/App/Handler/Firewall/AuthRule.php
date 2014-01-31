<?php

namespace Craft\App\Handler\Firewall;

use Craft\App\Roadmap;
use Craft\Box\Text\String;
use Craft\Data\Auth;
use Craft\Pattern\Specification\Item;
use Craft\Pattern\Specification\Rule;

class AuthRule extends Rule
{

    /**
     * Check if this match the rule
     * @param Item $item
     * @return bool
     */
    protected function match(Item $item)
    {
        if($item instanceof Roadmap) {
            return $this->matchRoadmap($item);
        }

        return false;
    }

    /**
     * Match auth
     * @param Roadmap $roadmap
     * @return bool
     */
    protected function matchRoadmap(Roadmap $roadmap)
    {
        // check auth
        if(isset($roadmap->draft->metadata['auth']) and !Auth::allowed($roadmap->draft->metadata['auth'])) {

            $message = String::compose('Action ":target" forbidden : user(:rank) < action(:auth).', [
                'target'    => $roadmap->route->target,
                'rank'      => Auth::rank(),
                'auth'      => $roadmap->draft->metadata['auth']
            ]);
            $this->error($message);

            return false;
        }

        return true;
    }

}