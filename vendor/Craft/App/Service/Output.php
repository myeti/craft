<?php

namespace Craft\App\Service;

use Craft\App;
use Craft\Debug\Logger;

class Output extends App\Service
{

    /** @var array */
    protected $formats = ['json', 'raw'];

    /** @var string */
    protected $format;


    /**
     * Select format
     * @param string $format
     * @throws \InvalidArgument
     */
    public function __construct($format = 'raw')
    {
        if(!in_array($foramt, $this->formats)) {
            throw new \InvalidArgument('Unknown format "' . $format . '"');
        }

        $this->format = $format;
    }


    /**
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return ['kernel.response' => 'onKernelResponse'];
    }


    /**
     * Handle response
     * @param App\Response $response
     * @param App\Request $request
     */
    public function onKernelResponse(App\Request $request, App\Response $response = null)
    {
        $this->{$this->format}($response->data);
    }


    /**
     * Render as json
     * @param array $data
     */
    protected function json(array $data)
    {
        $response = App\Response::json($response->data);
        Logger::info('Render json');
    }

} 