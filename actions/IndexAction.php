<?php

namespace voskobovich\rest\base\actions;

use voskobovich\rest\base\forms\IndexFormAbstract;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
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
     * function ($form, $action) {
     *     return new ActiveDataProvider([
     *         'query' => $form->buildQuery(),
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

        return $this->prepareProvider();
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return array
     * @throws InvalidConfigException
     */
    protected function prepareProvider()
    {
        $params = Yii::$app->request->get();

        /* @var $formClass \yii\base\Model */
        $formClass = $this->formClass;
        if (!$formClass instanceof IndexFormAbstract) {
            throw new InvalidConfigException('Property "formClass" must be implemented "voskobovich\rest\base\forms\IndexFormAbstract"');
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        /* @var $form IndexFormAbstract */
        $form = new $formClass(['query' => $modelClass::find()]);
        $form->setAttributes($params);

        if (!$form->validate()) {
            return $form;
        }

        if ($this->prepareProvider !== null) {
            return call_user_func($this->prepareProvider, $form, $this);
        }

        return new ActiveDataProvider([
            'query' => $form->buildQuery(),
        ]);
    }
}