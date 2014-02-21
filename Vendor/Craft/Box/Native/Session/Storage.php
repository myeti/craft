<?php
/**
 * Created by PhpStorm.
 * User: Aymeric
 * Date: 21.02.14
 * Time: 13:55
 */

namespace Craft\Box\Native\Session;

use Craft\Box\Provider\SessionProvider;
use Craft\Data\Repository\ScalarReplica;

class Storage extends ScalarReplica implements SessionProvider
{

    /** @var string */
    protected $name;

    /**
     * init session storage
     * @param string $name
     */
    public function __construct($name)
    {
        // start
        if(!session_id()) {
            ini_set('session.use_trans_sid', 0);
            ini_set('session.use_only_cookies', 1);
            ini_set("session.cookie_lifetime", 604800);
            ini_set("session.gc_maxlifetime", 604800);
            session_set_cookie_params(604800);
            session_start();
        }

        // init storage provider
        $this->name = $name;
        $session = isset($_SESSION[$name]) ? $_SESSION[$name] : [];

        parent::__construct($session);
    }


    /**
     * Get session id
     * @return string
     */
    public function id()
    {
        return session_id();
    }


    /**
     * Destroy session
     * @return bool
     */
    public function clear()
    {
        $this->exchangeArray([]);
        $this->replicate();
    }


    /**
     * Replicate inner data into external source
     * @return mixed
     */
    public function replicate()
    {
        $_SESSION[$this->name] = $this->getArrayCopy();
    }

}