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

class Markdown
{

    /** @var array */
    protected $_items = [];

    /**
     * Setup basic items
     */
    public function __construct()
    {
        // h1
        $this->set('h1', function($text) {
            return preg_replace('/(\s|^)# (.+)/', '$1<h1>$2</h1>$3', $text);
        });
        $this->set('h1-alt', function($text) {
            return preg_replace('/(.+)\n(={2,})\n/', "<h1>$1</h1>\n\n", $text);
        });

        // h2
        $this->set('h2', function($text) {
            return preg_replace('/(\s|^)## (.+)/', '$1<h2>$2</h2>$3', $text);
        });
        $this->set('h2-alt', function($text) {
            return preg_replace('/(.+)\n(-{2,})\n/', "<h2>$1</h2>\n\n", $text);
        });

        // h3
        $this->set('h3', function($text) {
            return preg_replace('/(\s|^)### (.+)/', '$1<h3>$2</h3>$3', $text);
        });

        // h4
        $this->set('h4', function($text) {
            return preg_replace('/(\s|^)#### (.+)/', '$1<h4>$2</h4>$3', $text);
        });

        // h5
        $this->set('h5', function($text) {
            return preg_replace('/(\s|^)##### (.+)/', '$1<h5>$2</h5>$3', $text);
        });

        // h6
        $this->set('h6', function($text) {
            return preg_replace('/(\s|^)###### (.+)/', '$1<h6>$2</h6>', $text);
        });

        // ul
        $this->set('ul', function($text){
            return preg_replace_callback('/(\n\* .+)+/', function($matches){

                // split
                $tab = preg_split('/\n/', $matches[0], -1, PREG_SPLIT_NO_EMPTY);

                // make ul
                $list = "<ul>";
                foreach($tab as $li) {
                    $list.= "\n<li>" . preg_replace('/\* (.+)/', '$1', $li) . "</li>";
                }
                $list .= "\n</ul>\n";

                return $list;

            }, $text);
        });

        // ul alt
        $this->set('ul-alt', function($text){
            return preg_replace_callback('/((\n|^)[ ]*- .+)+/', function($matches) {

                // split
                $tab = preg_split('/\n/', $matches[0], -1, PREG_SPLIT_NO_EMPTY);
                $stuck = $level = null;

                // make ul
                $list = "<ul>";
                foreach($tab as $i => $li) {

                    // get spaces
                    $spaces = strlen(preg_replace('/([ ]*)- .+/', '$1', $li));
                    if($spaces > 0) {
                        $spaces /= 4;
                    }

                    // get level
                    if($i == 0) {
                        $level = $spaces;
                    }

                    // inner tab
                    if($spaces > $level) {
                        if($stuck == null) {
                            $stuck = $i;
                            $tab[$stuck] = $li;
                        }
                        else {
                            $tab[$stuck] .= "\n" . $li;
                            unset($tab[$i]);
                        }
                    }
                    elseif($stuck != null) {
                        $list .= "\n" . str_repeat("\t", $level) . "<li>\n" . $this->apply('ul-alt', $tab[$stuck]) . '</li>';
                        $stuck = null;
                    }
                    else {
                        $list.= "\n" . str_repeat("\t", $level) . "<li>" . preg_replace('/[ ]*- (.+)/', '$1', $li) . "</li>";
                    }

                }
                $list .= "\n</ul>\n";

                return $list;

            }, $text);
        });

        // ol
        $this->set('ol', function($text){
            return preg_replace_callback('/(\n[0-9]+\. .+\n)+/', function($matches){

                // split
                $tab = preg_split('/\n/', $matches[0], -1, PREG_SPLIT_NO_EMPTY);

                // make ul
                $list = "<ol>";
                foreach($tab as $li) {
                    $list.= "\n<li>" . preg_replace('/[0-9]+\. (.+)/', '$1', $li) . "</li>";
                }
                $list .= "\n</ol>\n";

                return $list;

            }, $text);
        });

        // block code
        $this->set('pre-code', function($text){
            return preg_replace_callback('/(\n[ ]{4,}.*)+/', function($matches){
                return "<pre><code>" . $matches[0] . "\n</code></pre>\n";
            }, $text);
        });
        $this->set('pre-code-alt', function($text){
            return preg_replace('/`{3,}(.+)?\n(.+\n)*`{3,}/', "<pre><code lang=\"$1\">\n$2</code></pre>\n", $text);
        });

        // inline code
        $this->set('code',function($text){
            return preg_replace('/`(.+)`/', '<code>$1</code>', $text);
        });

        // quote
        $this->set('blockquote', function($text){
            return preg_replace_callback('/(\n>.*)+/', function($matches){

                // split
                $tab = preg_split('/\n/', $matches[0], -1, PREG_SPLIT_NO_EMPTY);

                // parse quote
                foreach($tab as $key => $line) {
                    $tab[$key] = trim(preg_replace('/>(.*)/', "$1", $line));
                }

                // re process
                $quote = "<blockquote>\n\n" . implode("\n\n", $tab) . "\n<blockquote>";
                $quote = $this->perform($quote, ['blockquote']);

                return "\n" . $quote;

            }, $text);
        });

        // p, after all block
        $this->set('p', function($text){

            // split
            $tab = preg_split('/\n{2,}/', $text, -1, PREG_SPLIT_NO_EMPTY);

            // make p when subtext starts not with "<"
            foreach($tab as $key => $subtext) {
                if(!preg_match('/^ *</', $subtext)) {
                    $tab[$key] = '<p>' . $subtext . '</p>';
                }
            }

            return implode("\n\n", $tab);
        });

        // link
        $this->set('a',function($text){
            $text = preg_replace('/([^!])\[(.+)\]\((.+) "(.+)"\)/', '$1<a href="$3" title="$4">$2</a>', $text);
            return preg_replace('/([^!])\[(.+)\]\((.+)\)/', '$1<a href="$3">$2</a>', $text);
        });

        // image
        $this->set('img',function($text){
            $text = preg_replace('/!\[(.+)\]\((.+) "(.+)"\)/', '<img src="$2" alt="$1" title="$3" />', $text);
            return preg_replace('/!\[(.+)\]\((.+)\)/', '<img src="$2" alt="$1" />', $text);
        });

        // strong
        $this->set('strong', function($text){
            $text = preg_replace('/( |^)__(.+)__/', '$1<strong>$2</strong>', $text);
            return preg_replace('/( |^)\*\*(.+)\*\*/', '$1<strong>$2</strong>', $text);
        });

        // em
        $this->set('em', function($text){
            $text = preg_replace('/( |^)_(.+)_/', '$1<em>$2</em>', $text);
            return preg_replace('/( |^)\*(.+)\*/', '$1<em>$2</em>', $text);
        });
    }


    /**
     * Add transform rule
     * @param string $name
     * @param callable $callback
     */
    public function set($name, \Closure $callback)
    {
        $this->_items[$name] = $callback;
    }


    /**
     * Remove rule
     * @param string $name
     */
    public function remove($name)
    {
        // multi removing
        if(is_array($name)) {
            foreach($name as $subname) {
                $this->remove($subname);
            }
        }

        unset($this->_items[$name]);
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
        foreach($this->_items as $name => $item) {

            // skip ?
            if(in_array($name, $skip)) {
                continue;
            }

            $text = $item($text);
        }

        return $text;
    }

    /**
     * Apply a single rule
     * @param $name
     * @param $text
     * @return string
     */
    public function apply($name, $text)
    {
        if(isset($this->_items[$name])) {
            return call_user_func($this->_items[$name], $text);
        }

        return $text;
    }

}