<?php

namespace voskobovich\api\actions;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * Class CreateAction.
 */
class CreateAction extends BaseAction
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var string the name of the view action. This property is need to create the URL when the model is successfully created.
     */
    public $viewAction = 'view';

    /**
     * Creates a new model.
     *
     * @throws ServerErrorHttpException  if there is any error when creating the model
     * @throws UnauthorizedHttpException
     *
     * @return \yii\db\ActiveRecord if there is any error when creating the model
     */
    public function run()
    {
        $this->runAccessControl();

        /* @var $model \yii\db\ActiveRecord */
        $model = Yii::createObject(
            $this->modelClass,
            ['scenario' => $this->scenario]
        );

        $params = Yii::$app->getRequest()->getBodyParams();
        $model->load($params, '');

        $validate = Yii::$app->request->get('validate', false);
        if (!$validate) {
            if ($model->save()) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(201);
                $id = implode(',', array_values($model->getPrimaryKey(true)));
                $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
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
