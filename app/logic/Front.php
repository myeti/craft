<?php

namespace my\logic;

class Front
{

    /**
     * Landing page
     * @view views/front.index.php
     */
    public function index()
    {

    }

    /**
     * About page
     * @view views/front.index.php
     */
    public function about()
    {
        return ['author' => 'Aymeric Assier'];
    }

}