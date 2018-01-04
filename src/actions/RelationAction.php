<?php

namespace voskobovich\api\actions;

use voskobovich\api\forms\RelationFormAbstract;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\web\UnauthorizedHttpException;

/**
 * Class RelationAction.
 */
class RelationAction extends BaseAction
{
    /**
     * @var string class name of the form which will be handled by this action.
     *             This property must be set.
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
     *
     * @return RelationFormAbstract|ActiveDataProvider
     * @throws \yii\base\InvalidParamException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @throws UnauthorizedHttpException
     */
    public function run($id)
    {
        /** @var \yii\db\ActiveRecord $model */
        $model = $this->findModel($id);

        $this->runAccessControl([
            'model' => $model,
        ]);

        return $this->prepareProvider($model);
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     *
     * @param \yii\db\ActiveRecord $model
     *
     * @throws \yii\base\InvalidParamException
     * @throws InvalidConfigException
     *
     * @return mixed|RelationFormAbstract|ActiveDataProvider
     */
    protected function prepareProvider($model)
    {
        /* @var $form RelationFormAbstract */
        $form = Yii::createObject($this->formClass);

        if (!$form instanceof RelationFormAbstract) {
            throw new InvalidConfigException('Property "formClass" must be implemented "voskobovich\api\forms\RelationFormAbstract"');
        }

        $params = Yii::$app->getRequest()->get();
        $form->load($params, '');

        if (!$form->validate()) {
            return $form;
        }

        if ($this->prepareProvider !== null) {
            return \call_user_func($this->prepareProvider, $form, $model, $this);
        }

        return new ActiveDataProvider([
            'query' => $form->buildQuery($model),
        ]);
    }
}
