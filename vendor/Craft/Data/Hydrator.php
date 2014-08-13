<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Data;

trait Hydrator
{

    /**
     * Hydrate properties from array
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach($data as $prop => $value) {
            if(property_exists($this, $prop)) {
                $this->{$prop} = $value;
            }
        }
    }

} 