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

require 'meta\Loader.php';

craft\meta\Loader::register();

craft\meta\Loader::vendors([
    'craft' => __DIR__,
    'my'    => dirname($_SERVER['SCRIPT_FILENAME'])
]);

craft\meta\Loader::aliases([
    'craft\Loader'      => 'craft\meta\Loader',
    'craft\Session'     => 'craft\data\Session',
    'craft\Flash'       => 'craft\data\Flash',
    'craft\Auth'        => 'craft\data\Auth',
    'craft\Env'         => 'craft\data\Env',
    'craft\Bag'         => 'craft\data\Bag',
    'craft\Mog'         => 'craft\data\Mog',
    'craft\Syn'         => 'craft\db\Syn',
    'craft\Model'       => 'craft\db\Model',
    'craft\Context'     => 'craft\core\Context',
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

craft\data\Session::init();
craft\data\Flash::init();
craft\data\Auth::init();
craft\data\Env::init();


/**
 * Load helpers
 */

require 'helpers.php';