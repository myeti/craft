<?php

require 'vendor/autoload.php';

/**
 * Making webapp should be fun, don't you think ?
 * We are bored of common too-serious class/method name.
 *
 * Shall we change this ? Right, let's try the steampunk world !
 */
use Craft\Steam\Engine,
    Craft\Steam\Zeppelin,
    Craft\Steam\Crew\Artist,
    Craft\Steam\Crew\Technician,
    Craft\Steam\Crew\Cartographer;

# In order to build a Zeppelin, we need a steam engine
$engine = new Engine;

# If the engine fails, prepare an emergency route
$engine->fail(404, '/lost');

# Our engine is ready, it's time to build our Zeppelin !
$zeppelin = new Zeppelin($engine);

# A Zeppelin can't fly alone, we need a crew :
# First, we need a cartographer with a map
$map = [
    '/'      => Zeppelin::go('home'),
    '/lost'  => Zeppelin::go('lost'),
];

$zeppelin->engage(new Cartographer($map));

# Then, we need a technician to work on the engine,
# and finally, an artist, to entertain our customers
$zeppelin->engage(new Technician);
$zeppelin->engage(new Artist);

# We are ready to takeoff :)
$zeppelin->fly();