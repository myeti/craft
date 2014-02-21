<?php

namespace Craft\Box\Native;

use Craft\Box\Provider\AuthProvider;
use Craft\Box\Provider\SessionProvider;
use Craft\Data\ArrayCollection;

class Auth implements AuthProvider
{

    /** @var SessionProvider */
    protected $session;


    /**
     * Bind to session
     */
    public function __construct()
    {
        $this->session = new Session\Storage('_craft/auth');
    }


    /**
     * Log user in
     * @param int $rank
     * @param mixed $user
     * @return bool
     */
    public function login($rank = 1, $user = null)
    {
        $this->session->set('rank', $rank);
        $this->session->set('user', $user);
    }


    /**
     * Get rank
     * @return int
     */
    public function rank()
    {
        return (int)$this->session->get('rank');
    }


    /**
     * Get user
     * @return mixed
     */
    public function user()
    {
        return $this->session->get('user');
    }


    /**
     * Log user out
     * @return bool
     */
    public function logout()
    {
        $this->session->clear();
    }

}