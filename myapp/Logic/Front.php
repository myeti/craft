<?php

/**
 * This is a controller, a class that contains many actions.
 * Use it to write all your business layer !
 */
namespace My\Logic;

use Craft\Data\Auth;
use Craft\Data\Env;
use Craft\Data\Flash;
use Craft\Data\Mog;
use Craft\Data\Session;
use My\Model\User;

/**
 * You can already define some metadata that will be applied
 * to all method, such as
 * - `@auth` : define the user rank required, if not enough, 403 is thrown
 * - `@render` : the template to use
 */
class Front
{

    /**
     * Your action !
     *
     * Whatever you do in this method, if you want to return data
     * to the view, return it as an array
     *
     * The next tag set the template to use (don't add .php)
     * @render views/front.hello
     *
     * @return array
     */
    public function hello()
    {
        return ['time' => date('H:i:s')];
    }


    /**
     * Action from url like 'url/with/:arg1'
     * @param string $arg1
     */
    public function actionWithArgs($arg1)
    {
        // need to redirect somewhere ?
        go('/somewhere/else');
    }


    /**
     * How to use models
     */
    public function models()
    {
        // find many
        $users = User::find();
        $users = User::find(['age' => 20]);

        // count
        $users = User::count();
        $users = User::count(['age' => 20]);

        // find one
        $user = User::one(['age' => 20]);
        $user = User::one(5); // id

        // save
        User::save($user);

        // delete
        User::drop($user);
        User::drop(5); // id
    }


    /**
     * How to use Session
     */
    public function session()
    {
        // session
        $foo = 'foo';

        // set data
        Session::set('foo', $foo);

        // get data
        $foo = Session::get('foo');

        // drop data
        Session::drop('foo');
    }


    /**
     * How to use Flash message
     * Message stored in session, but can only be retrieved once
     */
    public function flash()
    {
        Flash::set('success', 'Action done.');
        $message = Flash::get('success'); // 'Action done.'
        $message = Flash::get('success'); // null
    }


    /**
     * How to use Auth
     * Log in/out and store user object
     */
    public function auth()
    {
        $user = User::one();

        // login : rank, user object (optional)
        Auth::login(5, $user);

        // get data
        $rank = Auth::rank();
        $user = Auth::user();

        // check if logged in
        if(Auth::logged()) {

        }

        // logout
        Auth::logout();
    }


    /**
     * How to use Env
     * Much like a config storage : do not keep it over session
     */
    public function env()
    {
        Env::set('config.app', 'dev');
    }


    /**
     * Finally, your best friend !
     * Mog is a read-only helper and provide you data
     * from $_POST, $_GET, $_SERVER, headers and many more.
     *
     * @see craft\box\env\Mog for all methods !
     */
    public function mog()
    {
        $posts = Mog::post();
        $ip = Mog::ip();
        $url = Mog::url();
        $ajax = Mog::async();
    }

}