<?php

require 'vendor/autoload.php';

$session = new Craft\Box\Native\Session\Storage('craft/data');
$session->set('foo.bar.plop', 'bar');
//$session->clear();

var_dump($_SESSION);