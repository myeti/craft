<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Service;

use Craft\App;
use Craft\Orm\Syn;
use Craft\Trace\Logger;

/**
 * Inject a model when @map is specified.
 *
 * Needs Service\ResolverService
 */
class MapperService extends App\Service
{

    /** @var callable[] */
    protected $seekers = [];


    /**
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return ['kernel.request' => 'onKernelRequest'];
    }


    /**
     * Define model seeker
     * @param $model
     * @param callable $seeker
     * @return $this
     */
    public function define($model, callable $seeker)
    {
        $this->seekers[$model] = $seeker;
        return $this;
    }


    /**
     * Handle request
     * @param App\Request $request
     * @throws App\Internal\NotFound
     * @throws \InvalidArgumentException
     */
    public function onKernelRequest(App\Request $request)
    {
        // mapping requested
        if(!empty($request->meta['map'])) {

            // parse meta (@map My\Model:prop)
            list($model, $property) = explode(':', $request->meta['map']);

            // get entity
            $entity = isset($this->seekers[$model])
                ? call_user_func_array($this->seekers[$model], [$request, $property])
                : Syn::one($model, [$property => $request->args[$property]]);

            // not found
            if(!$entity) {
                throw new App\Internal\NotFound($model . '[' . $property . ':' . $request->args[$property] . '] not found.');
            }

            // replace property with entity
            $request->args[$property] = $entity;
            Logger::info('Model ' . $model . ' mapped into $' . $property);
        }
    }

}