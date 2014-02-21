<?php

namespace Craft\Box\Provider;

interface AuthProvider
{

    /**
     * Log user in
     * @param int $rank
     * @param mixed $user
     * @return bool
     */
    public function login($rank = 1, $user = null);


    /**
     * Get rank
     * @return int
     */
    public function rank();


    /**
     * Get user
     * @return mixed
     */
    public function user();


    /**
     * Log user out
     * @return bool
     */
    public function logout();

} 