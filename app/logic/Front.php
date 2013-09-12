<?php

namespace my\logic;

class Front
{

    /**
     * Landing page
     * @view view/front.index.php
     */
    public function index()
    {

    }

    /**
     * About page
     * @view view/front.index.php
     */
    public function about()
    {
        return ['author' => 'Aymeric Assier'];
    }

}