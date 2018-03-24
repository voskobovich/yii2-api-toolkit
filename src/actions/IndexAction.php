<?php

namespace voskobovich\api\actions;

use voskobovich\api\forms\IndexFormAbstract;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecordInterface;
use yii\web\UnauthorizedHttpException;

/**
 * Class IndexAction.
 */
class IndexAction extends BaseAction
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
     * @throws \yii\base\InvalidParamException
     * @throws \yii\base\InvalidConfigException
     * @throws UnauthorizedHttpException
     *
     * @return IndexFormAbstract|ActiveDataProvider
     */
    public function run()
    {
        $this->runAccessControl();

        /** @var \yii\db\ActiveRecord $model */
        $model = Yii::createObject($this->modelClass);

        return $this->prepareProvider($model);
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     *
     * @param ActiveRecordInterface $model
     *
     * @throws \yii\base\InvalidArgumentException
     * @throws \yii\base\InvalidParamException
     * @throws InvalidConfigException
     *
     * @return IndexFormAbstract|ActiveDataProvider
     */
    protected function prepareProvider($model)
    {
        /* @var $form IndexFormAbstract */
        $form = new $this->formClass();

        if (false === $form instanceof IndexFormAbstract) {
            throw new InvalidConfigException(
                'Property "formClass" must be implemented "voskobovich\api\forms\IndexFormAbstract"'
            );
        }

        $params = Yii::$app->getRequest()->get();
        $form->load($params, '');

        if (false === $form->validate()) {
            return $form;
        }

        if (null !== $this->prepareProvider) {
            return \call_user_func($this->prepareProvider, $form, $model, $this);
        }

        /** @var \yii\db\ActiveRecord $model */
        $model = new $this->modelClass;

        return new ActiveDataProvider([
            'query' => $form->buildQuery($model),
        ]);
    }
}
