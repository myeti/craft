<?php

namespace my\logic;

class Front
{

    /**
     * Landing page
     * @views views/front.index.php
     */
    public function index()
    {
        go('/welcome');
    }


    /**
     * Hey, welcome !
     * @views views/front.welcome.php
     */
    public function welcome()
    {

    }

}