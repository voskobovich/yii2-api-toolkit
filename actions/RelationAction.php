<?php

namespace voskobovich\rest\base\actions;

use voskobovich\data\CollectionProvider;
use voskobovich\rest\base\forms\RelationFormAbstract;
use Yii;
use yii\base\InvalidConfigException;
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
     * @var integer max limit value.
     */
    public $maxLimit = 1000;

    /**
     * @var callable a PHP callable that will be called to prepare a CollectionProvider that
     * should return a collection of the models. If not set, [[prepareCollectionProvider()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($action) {
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return an CollectionProvider object.
     */
    public $prepareCollectionProvider;

    /**
     * @param $id
     * @return CollectionProvider
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $this->prepareCollectionProvider($model);
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @param $model
     * @return array
     * @throws InvalidConfigException
     */
    protected function prepareCollectionProvider($model)
    {
        if ($this->prepareCollectionProvider !== null) {
            return call_user_func($this->prepareCollectionProvider, $this, $model);
        }

        $params = Yii::$app->request->get();

        /* @var $formClass \yii\base\Model */
        $formClass = $this->formClass;
        if (!$formClass instanceof RelationFormAbstract) {
            throw new InvalidConfigException('Property "formClass" must be implemented "voskobovich\rest\base\forms\RelationFormAbstract"');
        }

        /* @var $form RelationFormAbstract */
        $form = new $formClass(['model' => $model]);
        $form->setAttributes($params);

        if (!$form->validate()) {
            return $form;
        }

        return new CollectionProvider([
            'query' => $form->buildQuery(),
            'maxLimit' => $this->maxLimit
        ]);
    }
}