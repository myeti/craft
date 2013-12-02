<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box;

abstract class Lipsum
{

    /**
     * @const int configuration
     *
     * DEFAULT_*    define the default number of item
     * MIN_*        define the mininum range on random
     * MAX_*        define the maximum range on random
     */
    const DEFAULT_WORDS = 1;
    const MIN_WORDS = 8;
    const MAX_WORDS = 15;
    const DEFAULT_LINES = 1;
    const MIN_LINES = 4;
    const MAX_LINES = 8;
    const DEFAULT_PARAGRAPHES = 1;
    const MIN_PARAGRAPHES = 1;
    const MAX_PARAGRAPHES = 4;

    /**
     * @const string
     * default open and close tag for paragraphes
     */
    const OPEN_TAG = '<p>';
    const CLOSE_TAG = '</p>';

    /**
     * @var array
     * Contains all the LoremIpsum words
     */
    protected static $words = [
        'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 'curabitur', 'vel', 'hendrerit', 'libero',
        'eleifend', 'blandit', 'nunc', 'ornare', 'odio', 'ut', 'orci', 'gravida', 'imperdiet', 'nullam', 'purus', 'lacinia', 'a',
        'pretium', 'quis', 'congue', 'praesent', 'sagittis', 'laoreet', 'auctor', 'mauris', 'non', 'velit', 'eros', 'dictum',
        'proin', 'accumsan', 'sapien', 'nec', 'massa', 'volutpat', 'venenatis', 'sed', 'eu', 'molestie', 'lacus', 'quisque',
        'porttitor', 'ligula', 'dui', 'mollis', 'tempus', 'at', 'magna', 'vestibulum', 'turpis', 'ac', 'diam', 'tincidunt', 'id',
        'condimentum', 'enim', 'sodales', 'in', 'hac', 'habitasse', 'platea', 'dictumst', 'aenean', 'neque', 'fusce', 'augue',
        'leo', 'eget', 'semper', 'mattis', 'tortor', 'scelerisque', 'nulla', 'interdum', 'tellus', 'malesuada', 'rhoncus', 'porta',
        'sem', 'aliquet', 'et', 'nam', 'suspendisse', 'potenti', 'vivamus', 'luctus', 'fringilla', 'erat', 'donec', 'justo',
        'vehicula', 'ultricies', 'varius', 'ante', 'primis', 'faucibus', 'ultrices', 'posuere', 'cubilia', 'curae', 'etiam',
        'cursus', 'aliquam', 'quam', 'dapibus', 'nisl', 'feugiat', 'egestas', 'class', 'aptent', 'taciti', 'sociosqu', 'ad',
        'litora', 'torquent', 'per', 'conubia', 'nostra', 'inceptos', 'himenaeos', 'phasellus', 'nibh', 'pulvinar', 'vitae',
        'urna', 'iaculis', 'lobortis', 'nisi', 'viverra', 'arcu', 'morbi', 'pellentesque', 'metus', 'commodo', 'ut', 'facilisis',
        'felis', 'tristique', 'ullamcorper', 'placerat', 'aenean', 'convallis', 'sollicitudin', 'integer', 'rutrum', 'duis',
        'est', 'etiam', 'bibendum', 'donec', 'pharetra', 'vulputate', 'maecenas', 'mi', 'fermentum', 'consequat', 'suscipit',
        'aliquam', 'habitant', 'senectus', 'netus', 'fames', 'quisque', 'euismod', 'curabitur', 'lectus', 'elementum', 'tempor'
    ];

    /**
     * @var array
     * Many domain extensions
     */
    protected static $exts = ['.com', '.fr', '.net', '.org', '.info'];


    /**
     * Generate $nw words separeted with space
     *
     * @param int $nw : number of words
     * @param bool $toArray
     * @return mixed
     */
    public static function word($nw = self::DEFAULT_WORDS, $toArray = false)
    {
        $extract = (array)array_rand(array_flip(static::$words), (int)$nw);
        return (true === $toArray) ? $extract : ucfirst(implode(' ', $extract));
    }


    /**
     * Generate $n lines of $words words separeted with spaces and ends with a point
     *
     * @param int $nl : number of lines
     * @param int $nw : number of words
     * @param bool $toArray
     * @return mixed
     */
    public static function line($nl = self::DEFAULT_LINES, $nw = null, $toArray = false)
    {
        foreach($sentences = range(1, (int)$nl) as $key => $value) {
            $words = $nw ?: rand(static::MIN_WORDS, static::MAX_WORDS);
            $sentences[$key] = static::word($words) . '.';
        }

        return (true === $toArray) ? $sentences : implode(" \n", $sentences);
    }


    /**
     * Generate $n paragraph of $lines lines of $words words and might be wrapped with OPEN_TAG / CLOSE_TAG
     *
     * @param int $np : number of paragraphes
     * @param int $nl : number of lines
     * @param int $nw : number of words
     * @param bool $wrapping
     * @param bool $toArray
     * @return mixed
     */
    public static function paragraph($np = self::DEFAULT_PARAGRAPHES, $nl = null, $nw = null, $wrapping = false, $toArray = false)
    {
        foreach($paragraphes = range(1, (int)$np) as $key => $value) {
            $lines = $nl ?: rand(static::MIN_LINES, static::MAX_LINES);
            $paragraphes[$key] = (true === $wrapping)
                ? static::OPEN_TAG . static::line($lines, $nw) . static::CLOSE_TAG
                : static::line($lines, $nw);
        }

        return (true === $toArray) ? $paragraphes : implode("\n\n", $paragraphes);
    }


    /**
     * Generate a sentence of 3..6 words without ending point
     * @return string
     */
    public static function title()
    {
        return static::word(rand(3, 6));
    }


    /**
     * Generate a random email address
     * @return string
     */
    public static function email()
    {
        $ext = static::$exts;
        $email = static::word() . '@' . static::word() . array_rand(array_flip($ext));
        return strtolower($email);
    }


    /**
     * Generate a random url
     * @return string
     */
    public static function url()
    {
        $ext = static::$exts;
        $url = 'http://www.' . static::word() . array_rand(array_flip($ext));
        return strtolower($url);
    }


    /**
     * Generate two random word
     * @return string
     */
    public static function username()
    {
        return ucwords(static::word(2));
    }


    /**
     * Generate a poetry formatted text
     * @return string
     */
    public static function poetry()
    {
        $text = static::paragraph(4, 5, 6, true);
        return str_replace('.', '.<br>', $text);
    }

}