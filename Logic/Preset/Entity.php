<?php

namespace My\Logic\Preset;

use Craft\Env\Flash;
use Craft\Orm\Model\NullModel;


/**
 * Replace 'NullModel' with your Model
 * and 'entity' | 'entities' with your alias name
 */
class Entity
{

    /**
     * Get all items
     * @render views\entity.all
     * @return array
     */
    public function all()
    {
        $items = NullModel::find();
        return ['entities' => $items];
    }


    /**
     * Get one item
     * @render views\entity.one
     * @param  string $id
     * @return array
     */
    public function one($id)
    {
        $item = NullModel::one($id);
        return ['entity' => $item];
    }


    /**
     * Create or edit entity
     * @param null $id
     * @render views/entity.form
     * @return array
     */
    public function form($id = null)
    {
        // get entity
        $model = $id ? NullModel::one($id) : new NullModel();

        // does not exist
        if($id and !$model) {
            Flash::set('entity.error', 'entity #' . $id .' not found.');
        }
        // form attempt
        elseif($data = post()) {

            // valid data here

            // save
            $model = hydrate($model, $data);
            NullModel::save($model);

            // success
            Flash::set('form.success', $id ? 'entity updated.' : 'entity created.');
            go('/entity/', $model->id);

        }

        return ['model' => $model];
    }

    /**
     * Delete item
     * @render views\entity.delete
     * @param  string $id
     */
    public function delete($id)
    {
        NullModel::drop($id);
    }

} 