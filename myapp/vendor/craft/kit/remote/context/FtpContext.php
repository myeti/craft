<?php

namespace craft\kit\remote\context;

use craft\kit\remote\StreamContext;

class FtpContext implements StreamContext
{

    /** @var bool */
    public $secure = false;

    /** @var bool */
    public $overwrite = false;

    /** @var int */
    public $resume_pos = 0;

    /** @var string */
    public $proxy;


    /**
     * Prepare opts
     * @return array
     */
    public function opts()
    {
        // define wrapper
        $wrapper = $this->secure ? 'ftps' : 'ftp';

        // build opts
        $opts = [
            $wrapper => [
                'overwrite'    => $this->overwrite,
                'resume_pos'   => $this->resume_pos,
            ]
        ];

        // proxy
        if($this->proxy) {
            $opts[$wrapper]['proxy'] = $this->proxy;
        }

        return $opts;
    }

}