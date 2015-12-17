<?php

namespace voskobovich\rest\base\actions;

use voskobovich\rest\base\forms\RelationFormAbstract;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\rest\Action;


/**
 * Class RelationAction
 * @package voskobovich\rest\base\actions
 */
class RelationAction extends Action
{
    /**
     * @var string class name of the form which will be handled by this action.
     * This property must be set.
     */
    public $formClass;

    /**
     * @var callable a PHP callable that will be called to prepare a data provider that
     *
     * ```php
     * function ($form, $model, $action) {
     *     return new ActiveDataProvider([
     *         'query' => $form->buildQuery($model),
     *         'sort' => [
     *             ...
     *         ]
     *     ]);
     * }
     * ```
     *
     * The callable should return an Component object.
     */
    public $prepareProvider;

    /**
     * @param $id
     * @return Component
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $this->prepareProvider($model);
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @param $model
     * @return array
     * @throws InvalidConfigException
     */
    protected function prepareProvider($model)
    {
        /* @var $form RelationFormAbstract */
        $form = new $this->formClass;
        if (!$form instanceof RelationFormAbstract) {
            throw new InvalidConfigException('Property "formClass" must be implemented "voskobovich\rest\base\forms\RelationFormAbstract"');
        }

        $params = Yii::$app->getRequest()->get();
        $form->load($params, '');

        if (!$form->validate()) {
            return $form;
        }

        if ($this->prepareProvider !== null) {
            return call_user_func($this->prepareProvider, $form, $model, $this);
        }

        return new ActiveDataProvider([
            'query' => $form->buildQuery($model)
        ]);
    }
}