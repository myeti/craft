<?php

namespace Craft\App\Response;

use Craft\App\Response;
use Craft\Error\FileNotFound;

class Download extends Response
{

    /** @var string */
    public $format = 'application/octet-stream';


    /**
     * New file download response
     * @param string $file
     * @param int $code
     * @param array $headers
     * @throws FileNotFound
     */
    public function __construct($file, $code = 200, array $headers = [])
    {
        // no file
        if(!file_exists($file)) {
            throw new FileNotFound('File "' . $file . '" not found.');
        }

        // set response
        $this->code = $code;
        $this->headers = $headers;

        // set special headers
        $this->header('Content-Transfer-Encoding', 'Binary');
        $this->header('Content-disposition', 'attachment; filename="' . basename($file) . '"');

        // add file content
        $this->content = file_get_contents($file);
    }

} 