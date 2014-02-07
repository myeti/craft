<?php

require '../vendor/autoload.php';

use Craft\View\Form;
use Craft\View\Form\Field\StringField;
use Craft\View\Form\Field\TextField;

$form = new Form('foo');
$form->add(new StringField('bar'));

$sub = $form->add(new Form('foo2'));
$sub->add(new TextField('bar2'));


echo $form->html();