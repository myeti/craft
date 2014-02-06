<?php

namespace Craft\Text\Markdown;

use Craft\Text\Markdown;

abstract class Tag
{

    /** @var Markdown */
    protected $md;

    /**
     * Hard reference to md
     * @param Markdown $md
     */
    public function __construct(Markdown &$md)
    {
        $this->md = $md;
    }

    /**
     * Transform md to html tag
     * @param string $text
     * @return string
     */
    abstract public function transform($text);

} 