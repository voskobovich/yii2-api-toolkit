<?php

namespace voskobovich\api\actions;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;


/**
 * Class UpdateAction
 * @package voskobovich\api\actions
 */
class UpdateAction extends Action
{
    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Updates an existing model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being updated
     * @throws ServerErrorHttpException if there is any error when updating the model
     */
    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->setScenario($this->scenario);

        $params = Yii::$app->getRequest()->getBodyParams();
        $model->load($params, '');

        $validate = Yii::$app->request->get('validate', false);
        if (!$validate) {
            if ($model->save() === false && !$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
            }
        } else {
            $parts = explode(',', $validate);
            $attributeNames = array_intersect($parts, $model->attributes());
            if (empty($attributeNames[0])) {
                $attributeNames = null;
            }
            $model->validate($attributeNames);
        }

        return $model;
    }
}