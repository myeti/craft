<?php

namespace Craft\View;

interface EngineInterface
{

    /**
     * Set views directory
     * @param string $dir
     */
    public function dir($dir);

    /**
     * Render template using data
     * @param string $template
     * @param array $data
     * @return string
     */
    public function render($template, $data = []);

} 