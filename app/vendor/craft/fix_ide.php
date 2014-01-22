<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */

/**
 * This file fix your IDE autocompleting class aliases (don't load it, it's magic).
 * You're welcome.
 */

namespace craft {
    class Loader extends \craft\kit\loader\Loader {}
    class Mog extends \craft\box\env\Mog {}
    class Session extends \craft\box\env\Session {}
    class Auth extends \craft\box\env\Auth {}
    class Flash extends \craft\box\env\Flash {}
    class Env extends \craft\box\env\Env {}
    class Cookie extends \craft\box\env\Cookie {}
    class Bag extends \craft\box\env\Bag {}
}

namespace craft\web {
    class App extends \craft\kit\app\App {}
    class Context extends \craft\kit\app\Context {}
}

namespace craft\cli {
    class Console extends \craft\kit\cli\Console {}
    class In extends \craft\kit\cli\In {}
    class Out extends \craft\kit\cli\In {}
}

namespace craft\view{
    class Template extends \craft\kit\view\Template {}
    class Html extends \craft\kit\view\helper\Html {}
}

namespace craft\dev {
    class Logger extends \craft\kit\logger\Logger {}
    class Tracker extends \craft\kit\tracker\Tracker {}
}

namespace craft\orm {
    class Syn extends \craft\kit\orm\Syn {}
    trait Model { use \craft\kit\orm\Model; }
    class PdoMapper extends \craft\kit\orm\map\PdoMapper {}
    class MySQL extends \craft\kit\orm\pdo\MySQL {}
    class SQLite extends \craft\kit\orm\pdo\SQLite {}
}
