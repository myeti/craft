<?php

namespace Craft\Http\Request;

class File
{

    const OK = 0;
    const MAX_SIZE = 1;
    const FORM_SIZE = 2;
    const PARTIAL = 3;
    const NO_FILE = 4;
    const NO_TMP_DIR = 6;
    const CANT_WRITE = 7;
    const ERR_EXTENSION = 8;

    /** @var string */
    public $name;

    /** @var string */
    public $ext;

    /** @var string */
    public $tmp;

    /** @var string */
    public $type;

    /** @var int */
    public $size;

    /** @var int */
    public $error = self::OK;


    /**
     * Create from $_FILES
     * @param array $file
     */
    public function __construct(array $file)
    {
        $this->name = basename($file['name']);
        $this->ext = pathinfo($this->name, PATHINFO_EXTENSION);
        $this->tmp = $file['tmp_file'];
        $this->type = $file['type'];
        $this->size = $file['size'];
        $this->error = (int)$file['error'];
    }


    /**
     * Save file to directory
     * @param string $dir
     * @param string $filename
     * @return bool
     */
    public function save($dir, $filename = null)
    {
        // clean
        $dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if(!$filename) {
            $filename = $this->name;
        }

        return move_uploaded_file($this->tmp, $dir . $filename);
    }

} 