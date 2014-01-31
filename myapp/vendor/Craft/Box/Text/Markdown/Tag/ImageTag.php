<?php

namespace Craft\Box\Text\Markdown\Tag;

use Craft\Box\Text\Markdown\Tag;

class ImageTag implements Tag
{

    /**
     * Transform md to html tag
     * @param string $text
     * @return string
     */
    public function transform($text)
    {
        $text = preg_replace('/!\[(.+)\]\((.+) "(.+)"\)/', '<img src="$2" alt="$1" title="$3" />', $text);
        return preg_replace('/!\[(.+)\]\((.+)\)/', '<img src="$2" alt="$1" />', $text);
    }

}