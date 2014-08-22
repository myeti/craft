<?php

namespace Craft\Net;

interface Request
{

    const GET = 'GET';
    const POST = 'POST';

    /**
     * Send request
     * @return mixed
     */
    public function send();

} 