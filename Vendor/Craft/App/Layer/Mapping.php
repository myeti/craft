<?php

namespace Craft\App\Layer;

use Craft\App\Event\NotFound;
use Craft\App\Layer;
use Craft\App\Request;
use Craft\Orm\Syn;

/**
 * Inject a model when @map is specified.
 *
 * Needs Layer\Metadata
 */
class Mapping extends Layer
{

    /**
     * Handle request
     * @param Request $request
     * @throws NotFound
     * @throws \InvalidArgumentException
     * @return Request
     */
    public function before(Request $request)
    {
        // mapping requested
        if(isset($request->meta['map'])) {

            // get class
            $class = $request->meta['map'];

            // error
            if(!class_exists($class)) {
                throw new \InvalidArgumentException('Class "' . $class . '" for mapping not found.');
            }

            // init model with id
            if(isset($request->args['id'])) {

                // load
                $model = Syn::one($class, $request->args['id']);

                // not found
                if(!$model) {
                    throw new NotFound('Model #' . $request->args['id'] . ' not found.');
                }

            }
            // without
            else {
                $model = new $class;
            }

            // replace as id
            $request->args['id'] = $model;
        }

        return $request;
    }

} 