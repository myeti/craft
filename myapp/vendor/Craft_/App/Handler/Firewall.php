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
use Craft\Box\Error\SomethingIsWrongException;
use Craft\Box\Text\String;
use Craft\Box\Data\Validator;
use Craft\Context\Auth;

class Firewall extends Handler
{

    /** @var Validator */
    protected $validator;


    /**
     * Create validator
     */
    public function __construct()
    {
        $this->validator = new Validator();

        // add auth rule
        if(!$this->validator->has('firewall.auth')) {
            $this->validator->set('firewall.auth', function(Roadmap $roadmap) {

                if(isset($roadmap->draft->metadata['auth']) and Auth::rank() < (int)$roadmap->draft->metadata['auth']) {

                    // format message
                    return String::compose('Action ":target" forbidden : user(:rank) < action(:auth).', [
                        'target'    => $roadmap->route->target,
                        'rank'      => Auth::rank(),
                        'auth'      => $roadmap->draft->metadata['auth']
                    ]);

                }

                return true;
            });
        }
    }


    /**
     * Get handler name
     * @return string
     */
    public function name()
    {
        return 'firewall';
    }


    /**
     * Handle an give back the env
     * @param Roadmap $roadmap
     * @throws SomethingIsWrongException
     * @return Roadmap
     */
    public function handleRoadmap(Roadmap $roadmap)
    {
        // apply checking
        $valid = $this->validator->valid($roadmap);

        // error 403
        if(!$valid->valid) {
            $roadmap->error = 403;
            throw new SomethingIsWrongException(implode(' ', $valid->errors), 403);
        }

        return $roadmap;
    }

}