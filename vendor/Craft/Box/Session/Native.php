<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box\Session;

use Craft\Data\Map;
use Craft\Data\ProviderInterface;

class Native extends Map implements ProviderInterface
{

    /** @var string */
    protected $name;


    /**
     * Start session
     * @param string $name
     */
    public function __construct($name)
    {
        // session name
        $this->name = $name;

        // config
        if(!session_id()) {
            ini_set('session.use_trans_sid', 0);
            ini_set('session.use_only_cookies', 1);
            ini_set("session.cookie_lifetime", 604800);
            ini_set("session.gc_maxlifetime", 604800);
            session_set_cookie_params(604800);
            session_start();
        }

        // get data
        $data = isset($_SESSION[$name]) ? $_SESSION[$name] : [];
        parent::__construct($data);
    }


    /**
     * Save data in session
     */
    public function save()
    {
        $_SESSION[$this->name] = $this->getArrayCopy();
    }


    /**
     * At last, save
     */
    public function __destruct()
    {
        $this->save();
    }

} 