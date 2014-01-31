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
use Craft\App\Handler\Firewall\AuthRule;
use Craft\App\Roadmap;
use Craft\Box\Error\SomethingIsWrongException;
use Craft\Pattern\Specification\Validator;

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
        $this->validator->set('firewall.auth', new AuthRule());
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
        $report = $this->validator->apply($roadmap);

        // error 403
        if(!$report->pass) {
            $roadmap->error = 403;
            throw new SomethingIsWrongException(implode(' ', $report->errors), 403);
        }

        return $roadmap;
    }

}