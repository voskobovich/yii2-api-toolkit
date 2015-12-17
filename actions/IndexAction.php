<?php

namespace voskobovich\rest\base\actions;

use voskobovich\rest\base\forms\IndexFormAbstract;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\rest\Action;


/**
 * Class IndexAction
 * @package voskobovich\rest\base\actions
 */
class IndexAction extends Action
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
     * @return Component
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var \yii\db\ActiveRecord $model */
        $model = new $this->modelClass;

        return $this->prepareProvider($model);
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @param ActiveRecord $model
     * @return array
     * @throws InvalidConfigException
     */
    protected function prepareProvider($model)
    {
        $params = Yii::$app->request->get();

        /* @var $form IndexFormAbstract */
        $form = new $this->formClass();

        if (!$form instanceof IndexFormAbstract) {
            throw new InvalidConfigException('Property "formClass" must be implemented "voskobovich\rest\base\forms\IndexFormAbstract"');
        }

        $form->setAttributes($params);

        if (!$form->validate()) {
            return $form;
        }

        if ($this->prepareProvider !== null) {
            return call_user_func($this->prepareProvider, $form, $model, $this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $model = new $this->modelClass;

        return new ActiveDataProvider([
            'query' => $form->buildQuery($model),
        ]);
    }
}