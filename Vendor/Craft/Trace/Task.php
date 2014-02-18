<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Trace;

class Task extends \ArrayObject
{

    /** @var string */
    public $name;

    /** @var array */
    public $start = [
        'time'   => 0,
        'memory' => 0
    ];

    /** @var array */
    public $stop = [
        'time'   => 0,
        'memory' => 0
    ];

    public $total = [
        'time'   => 0,
        'memory' => 0
    ];


    /**
     * Start task tracking
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->start = [
            'time' => microtime(true),
            'memory' => memory_get_usage(true)
        ];
    }


    /**
     * Stop task tracking
     * return array|bool
     */
    public function over()
    {
        $this->stop = [
            'time' => microtime(true),
            'memory' => memory_get_usage(true)
        ];

        $this->total = $this->report();

        return $this;
    }


    /**
     * Get current state
     * @return array
     */
    protected function report()
    {
        return [
            'time' => microtime(true) - $this->start['time'],
            'memory' => memory_get_usage(true) - $this->start['memory']
        ];
    }


    /**
     * Formatted report
     * @return array
     */
    public function __toString()
    {
        // get current state
        $report = ($this->total['time'] > 0)
            ? $this->total
            : $this->report();

        // prepare string
        $time = number_format($report['time'], 4);
        $memory = $report['memory'] / 1024;
        $string = '# ' . $this->name . ' : ' . $time . 'ms / ' . $memory . 'ko ';

        // state
        $string .= ($this->total['time'] > 0) ? '(finished)' : '(running)';

        return $string;
    }

} 