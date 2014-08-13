<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm\Model;

use Craft\Orm\Model;

trait Flagged
{

    use Model;

    /** @var int */
    public $id;

    /** @var string date */
    public $created;

    /** @var string date */
    public $updated;

} 