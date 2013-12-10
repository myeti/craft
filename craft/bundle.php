<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */


/*
 * Autoloader
 */

require 'core/Loader.php';

craft\core\Loader::register();

craft\core\Loader::vendors([
    'craft' => __DIR__,
    'my'    => dirname($_SERVER['SCRIPT_FILENAME'])
]);

craft\core\Loader::aliases([
    'craft\Loader'      => 'craft\core\Loader',
    'craft\Context'     => 'craft\core\Context',
    'craft\Session'     => 'craft\data\Session',
    'craft\Flash'       => 'craft\data\Flash',
    'craft\Auth'        => 'craft\data\Auth',
    'craft\Env'         => 'craft\data\Env',
    'craft\Bag'         => 'craft\data\Bag',
    'craft\Mog'         => 'craft\data\Mog',
    'craft\Syn'         => 'craft\db\Syn',
    'craft\Model'       => 'craft\db\Model',
]);


/*
 * Define base url
 */

define('APP_URL', dirname($_SERVER['SCRIPT_NAME']) . '/');


/*
 * Init context
 */

ini_set('session.use_trans_sid', 0);
ini_set('session.use_only_cookies', 1);
ini_set("session.cookie_lifetime", 604800);
ini_set("session.gc_maxlifetime", 604800);
session_set_cookie_params(604800);
session_start();

craft\Session::init();
craft\Flash::init();
craft\Auth::init();
craft\Env::init();
craft\Mog::init();


/**
 * Load helpers
 */

require 'helpers.php';