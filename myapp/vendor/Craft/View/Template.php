<?php

namespace Craft\View;

class Template extends Sandbox
{

    /**
     * Quick template forging
     * @param Engine $engine
     * @param string $template
     * @param array $data
     * @param array $sections
     * @return string
     */
    public static function forge(Engine $engine, $template, array $data = [], array $sections = [])
    {
        $sandbox = new self($engine);
        return $sandbox->render($template, $data, $sections);
    }

} 