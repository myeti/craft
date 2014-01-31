<?php

namespace Craft\Box\Text\Markdown;

interface Tag
{

    /**
     * Transform md to html tag
     * @param string $text
     * @return string
     */
    public function transform($text);

} 