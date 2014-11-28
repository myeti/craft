<?php

namespace Craft\View\Engine;

use Craft\View\Engine;

class Html extends Engine
{

    /** @var string */
    public $assets;


    /**
     * Init html engine
     * @param string $directory
     * @param string $assets
     * @param string $ext
     */
    public function __construct($directory, $assets = '/', $ext = '.php')
    {
        // native engine
        parent::__construct($directory, $ext);

        // assets url
        if(!$assets) {
            $assets = '/';
        }
        elseif($assets != '/') {
            $assets = rtrim($assets, '/') . '/';
        }
        $this->assets = $assets;

        // text helper
        $this->helper('e', '\Craft\Data\Text\String::escape');

        // box helper
        $this->helper('session', '\Craft\Box\Session::get');
        $this->helper('flash', '\Craft\Box\Flash::get');
        $this->helper('user', '\Craft\Box\Auth::user');
        $this->helper('rank', '\Craft\Box\Auth::rank');

        // assets helper
        $this->helper('asset', [$this, 'asset']);
        $this->helper('css', [$this, 'css']);
        $this->helper('js', [$this, 'js']);
    }


    /**
     * Get asset path
     * @param string $filename
     * @param string $ext
     * @return string
     */
    public function asset($filename, $ext = null)
    {
        if($ext) {
            $ext = '.' . ltrim($ext, '.');
        }
        return $this->assets . '/' . ltrim($filename, '/') . $ext;
    }


    /**
     * Css tag
     * @param $files
     * @return string
     */
    public function css(...$files)
    {
        $css = [];
        foreach($files as $file) {
            $css[] = '<link type="text/css" href="' . $this->asset($file, '.css') . '" rel="stylesheet" />';
        }
        return implode("\n    ", $css) . "\n";
    }


    /**
     * Js tag
     * @param $files
     * @return string
     */
    public function js(...$files)
    {
        $js = [];
        foreach($files as $file) {
            $js[] = '<script type="text/javascript" src="' . $this->asset($file, '.js')  . '"></script>';
        }
        return implode("\n    ", $js) . "\n";
    }

} 