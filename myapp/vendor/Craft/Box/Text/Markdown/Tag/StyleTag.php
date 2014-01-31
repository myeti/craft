<?php

namespace Craft\Box\Text\Markdown\Tag;

use Craft\Box\Text\Markdown\Tag;

class StyleTag extends Tag
{

    /**
     * Transform md to html tag
     * @param string $text
     * @return string
     */
    public function transform($text)
    {
        // strong
        $text = preg_replace('/( |^)__(.+)__/', '$1<strong>$2</strong>', $text);
        $text = preg_replace('/( |^)\*\*(.+)\*\*/', '$1<strong>$2</strong>', $text);

        // italic
        $text = preg_replace('/( |^)_(.+)_/', '$1<em>$2</em>', $text);
        return preg_replace('/( |^)\*(.+)\*/', '$1<em>$2</em>', $text);
    }

}