<?php

require 'vendor/autoload.php';

$session = new Craft\Box\Native\Session('_craft/data');
$session->set('foo.bar.plop', 'bar');
//$session->clear();

var_dump($session, $_SESSION);