<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Remote\MailServer;

class Attachment extends Part
{

    /** @var string */
    public $filename;


    /**
     * Download attachment
     * @param string $to
     * @param bool $force
     * @return int
     */
    public function download($to, $force = false)
    {
        // clean path
        $to = rtrim($to, '/\\') . DIRECTORY_SEPARATOR . $this->filename;

        // already exists
        if(!$force and file_exists($to)) {
            $to = rtrim($to, '/\\') . DIRECTORY_SEPARATOR . uniqid() . '-' . $this->filename;
        }

        return file_put_contents($to, $this->body);
    }

}