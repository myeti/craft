<?php

namespace Craft\App\Layer;

use Craft\App\Layer;
use Craft\App\Request;
use Craft\App\Response;
use Craft\Text\Markdown as Parser;

/**
 * Parse content to markdown.
 */
class Markdown extends Layer
{

    /**
     * Format markdown text
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function after(Request $request, Response $response)
    {
        $response->content = Parser::make($response->content);
        $response->stamp('markdown.parsed');

        return $response;
    }

} 