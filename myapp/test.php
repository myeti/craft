<?php

require 'vendor/Craft/Bundle/autoload.php';

use Craft\Box\Data\Repository;

$array = [
    'foo' => 'bar'
];

$test = new Repository($array);

$test->set('a.b', 'ab');

var_dump($test, $array);