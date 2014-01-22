<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\app\process;

use craft\box\text\String;
use craft\box\data\Validator;
use craft\kit\app\Context;
use craft\box\env\Auth;
use craft\kit\app\ContextHandler;
use craft\kit\dispatcher\DispatchException;

class FirewallHandler extends ContextHandler
{

    /** @var Validator */
    protected $_validator;


    /**
     * Create validator
     */
    public function __construct()
    {
        $this->_validator = new Validator();

        // add auth rule
        if(!$this->_validator->has('firewall.auth')) {
            $this->_validator->set('firewall.auth', function(Context $context) {

                if(isset($context->action->metadata['auth']) and Auth::rank() < (int)$context->action->metadata['auth']) {

                    // format message
                    return String::compose('Action ":target" forbidden : user(:rank) < action(:auth).', [
                        'target'    => $context->route->target,
                        'rank'      => Auth::rank(),
                        'auth'      => $context->action->metadata['auth']
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
     * @param Context $context
     * @throws DispatchException
     * @return Context
     */
    public function handleContext(Context $context)
    {
        // apply checking
        $valid = $this->_validator->valid($context);

        // error 403
        if(!$valid->valid) {
            $context->error = 403;
            throw new DispatchException(403, implode(' ', $valid->errors));
        }

        return $context;
    }

}