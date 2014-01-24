<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */

require 'package.php';

/**
 * Pre-define simple namespaces
 */

craft\Loader::aliases([

    # box env
    'craft\Mog'             => 'craft\box\env\Mog',
    'craft\Session'         => 'craft\box\env\Session',
    'craft\Auth'            => 'craft\box\env\Auth',
    'craft\Flash'           => 'craft\box\env\Flash',
    'craft\Env'             => 'craft\box\env\Env',
    'craft\Cookie'          => 'craft\box\env\Cookie',
    'craft\Bag'             => 'craft\box\env\Bag',

    # kit web
    'craft\web\App'         => 'craft\kit\web\App',
    'craft\web\Context'     => 'craft\kit\web\Context',
    'craft\web\StaticApp'   => 'craft\kit\web\preset\StaticApp',
    'craft\web\RestApp'     => 'craft\kit\web\preset\RestApp',

    # kit cli
    'craft\cli\Console'     => 'craft\kit\cli\Console',
    'craft\cli\In'          => 'craft\kit\cli\In',
    'craft\cli\Out'         => 'craft\kit\cli\In',

    # kit view
    'craft\view\Template'   => 'craft\kit\view\Template',

    # kit logger
    'craft\dev\Logger'      => 'craft\kit\logger\Logger',

    # kit tracker
    'craft\dev\Tracker'     => 'craft\kit\tracker\Tracker',

    # kit orm
    'craft\orm\Syn'         => 'craft\kit\orm\Syn',
    'craft\orm\Model'       => 'craft\kit\orm\Model',
    'craft\orm\PdoMapper'   => 'craft\kit\orm\map\PdoMapper',
    'craft\orm\MySQL'       => 'craft\kit\orm\pdo\MySQL',
    'craft\orm\SQLite'      => 'craft\kit\orm\pdo\SQLite',

]);