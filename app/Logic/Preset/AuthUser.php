<?php

namespace My\Logic\Preset;

use Forge\Auth;
use Forge\Flash;
use My\Entity\User;

class AuthUser
{

    /**
     * Attempt login
     * @render auth.login
     * @return mixed
     */
    public function login()
    {
        // login submitted
        if(post())
        {
            // login attempt
            if($user = Auth::basic('My\Model\User', post('username'), post('password'))) {

                // remove password for security
                $user->password = null;

                // redirect
                Flash::set('login.success', 'Welcome ' . $user->username . ' !');
                go('/');

            }
            else {
                Flash::set('login.fail', 'Bad user data.');
            }
        }

    }


    /**
     * Logout current user
     */
    public function logout()
    {
        Auth::logout();
        go('/');
    }


    /**
     * Register new user
     * @render auth.register
     * @return array
     */
    public function register()
    {
        // create user
        $user = new User();

        // register attempt
        if($data = post()) {

            // remove rank injection
            unset($data['rank']);

            // find user
            $exists = User::one([
                'username' => post('username')
            ]);

            // create user
            $user = hydrate($user, $data);

            // check if user already exists
            if($exists) {
                Flash::set('register.failed', 'Username already exists.');
            }
            // create + autologin
            else {
                User::save($user);
                Auth::login(1, $user);
                Flash::set('register.success', 'Welcome, ' . $user->username . '.');
                go('/');
            }

        }

        return ['user' => $user];
    }

} 