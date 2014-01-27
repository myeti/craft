<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box\text;

/**
 * Class Lipsum
 * @package craft\text
 *
 * Generate fake text based on lorem or cake ipsum.
 *
 * Usage :
 * - change source : Lipsum::source('cake')
 * - make word : Lipsum::word()
 * - make line : Lipsum::line([number_of_words])
 * - make text : Lipsum::text([number_of_words], [number_of_lines])
 * - make many texts : Lipsum::texts([number_of_words], [number_of_lines], [number_of_blocks])
 * - make title : Lipsum::title()
 * - make url : Lipsum::url()
 * - make username : Lipsum::username()
 * - make password : Lipsum::password()
 * - make email : Lipsum::email()
 * - make poetry : Lipsum::poetry()
 * - make date : Lipsum::date()
 * - make number : Lipsum::number()
 */
abstract class Lipsum
{

    /** @var array */
    protected static $lorem = [
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

    /** @var array */
    protected static $cake = ['cupcake', 'ipsum', 'dolor', 'sit', 'amet', 'pudding', 'donut', 'muffin', 'gingerbread', 'sweet',
        'roll', 'topping', 'marshmallow', 'sugar', 'plum', 'brownie', 'jelly', 'beans', 'toffee', 'cheesecake', 'candy', 'halvah',
        'oat', 'cake', 'cotton', 'ice', 'cream', 'wafer', 'tootsie', 'dragée', 'icing', 'macaroon', 'unerdwearcom', 'apple', 'pie',
        'lemon', 'drops', 'dessert', 'powder', 'pastry', 'liquorice', 'jujubes', 'gummies', 'fruitcake', 'bonbon', 'chocolate',
        'bar', 'danish', 'gummi', 'bears', 'lollipop', 'applicake', 'caramels', 'croissant', 'tiramisu', 'carrot', 'tart', 'bear',
        'claw', 'marzipan', 'lemon', 'cookie', 'canes', 'chupa', 'chups', 'soufflé', 'biscuit', 'jelly-o', 'marzipan', 'cotton',
        'marzipan', 'sesame', 'snaps', 'fruitcake', 'cupcake', 'fruitcake', 'cookie'
    ];

    /** @var array */
    protected static $exts = ['.com', '.fr', '.net', '.org', '.info'];


    /**
     * Get or set source
     * @param null $name
     * @return array
     */
    public static function source($name = null)
    {
        // set source
        static $source;
        if($name) {
            $source = $name;
        }
        elseif(!$source) {
            $source = 'lorem';
        }

        // get source array
        return array_flip(static::$$source);
    }


    /**
     * Generate Lipsum text
     * @param null $words
     * @param null $lines
     * @param null $texts
     * @return string
     */
    public static function generate($words = null, $lines = null, $texts = null)
    {
        // generate output
        $output = '';

        // texts
        $texts = $texts ?: rand(1, 5);
        for($i = 1; $i <= $texts; $i++) {

            // lines
            $lines = $lines ?: rand(2, 10);
            for($j = 1; $j <= $lines; $j++) {

                // words
                $words = $words ?: rand(6, 12);
                $line = implode(' ', array_rand(static::source(), $words));

                $output .= ucfirst($line) . '. ';
            }

            $output .= "\n";
        }

        return rtrim($output, "\n");
    }


    /**
     * Generate one word
     * @return string
     */
    public static function word()
    {
        return static::generate(1, 1, 1);
    }


    /**
     * Generate one line
     * @param null $words
     * @return string
     */
    public static function line($words = null)
    {
        return static::generate($words, 1, 1);
    }


    /**
     * Generate one text
     * @param null $words
     * @param null $lines
     * @return string
     */
    public static function text($words = null, $lines = null)
    {
        return static::generate($words, $lines, 1);
    }

    /**
     * Generate many text
     * @param null $words
     * @param null $lines
     * @param null $texts
     * @return string
     */
    public static function texts($words = null, $lines = null, $texts = null)
    {
        return static::generate($words, $lines, $texts);
    }


    /**
     * Generate a line of 3..6 words without ending point
     * @return string
     */
    public static function title()
    {
        return rtrim(static::word(rand(3, 6)), '. ');
    }


    /**
     * Generate a random email address
     * @return string
     */
    public static function email()
    {
        $ext = array_rand(array_flip(static::$exts));
        $email = static::word() . '@' . static::word() . $ext;
        return strtolower($email);
    }


    /**
     * Generate a random url
     * @return string
     */
    public static function url()
    {
        $ext = array_rand(array_flip(static::$exts));
        $url = 'http://www.' . static::word() . $ext;
        return strtolower($url);
    }


    /**
     * Generate two random word
     * @return string
     */
    public static function username()
    {
        return rtrim(ucwords(static::line(2)), '. ');
    }


    /**
     * Generate two random word
     * @return string
     */
    public static function password()
    {
        return sha1(static::line());
    }


    /**
     * Generate a poetry formatted text
     * @return string
     */
    public static function poetry()
    {
        return static::texts(4, 5, 6);
    }


    /**
     * Generate random date
     * @return string
     */
    public static function date()
    {
        $min = 381121281;
        $max = time();
        $time = rand($min, $max);
        return date('Y-m-d H:i:s', $time);
    }


    /**
     * Generate random int
     * @return int
     */
    public static function number()
    {
        return rand(0, 20000);
    }

}