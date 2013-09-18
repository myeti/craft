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
        go('/welcome');
    }


    /**
     * Hey, welcome !
     * @view view/front.welcome.php
     */
    public function welcome()
    {

    }

}