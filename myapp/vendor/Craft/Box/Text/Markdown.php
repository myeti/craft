<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box\Text;

use Craft\Box\Text\Markdown\Tag\CodeTag;
use Craft\Box\Text\Markdown\Tag\ImageTag;
use Craft\Box\Text\Markdown\Tag\LinkTag;
use Craft\Box\Text\Markdown\Tag\ListTag;
use Craft\Box\Text\Markdown\Tag\ParagraphTag;
use Craft\Box\Text\Markdown\Tag\QuoteTag;
use Craft\Box\Text\Markdown\Tag\StyleTag;
use Craft\Box\Text\Markdown\Tag\TitleTag;
use Craft\Box\Text\Markdown\Tag;

class Markdown
{

    /** @var Tag[] */
    protected $tags = [];


    /**
     * Setup basic tags
     */
    public function __construct()
    {
        $this->tags = [
            'title'     => new TitleTag(),
            'list'      => new ListTag(),
            'code'      => new CodeTag(),
            'quote'     => new QuoteTag(),
            'paragraph' => new ParagraphTag(),
            'link'      => new LinkTag(),
            'image'     => new ImageTag(),
            'style'     => new StyleTag(),
        ];
    }


    /**
     * Add tag transformation
     * @param $name
     * @param Tag $tag
     */
    public function add($name, Tag $tag)
    {
        $this->tags[$name] = $tag;
    }


    /**
     * Remove rule
     * @param string $name
     */
    public function drop($name)
    {
        unset($this->tags[$name]);
    }


    /**
     * Transform markdown text to html
     * @param string $text
     * @param array $skip
     * @return string
     */
    public function perform($text, $skip = [])
    {
        // clean
        $text = trim($text);

        // apply all rules
        foreach($this->tags as $name => $tag) {

            // skip ?
            if(in_array($name, $skip)) {
                continue;
            }

            $text = $tag->transform($text);
        }

        return $text;
    }

}