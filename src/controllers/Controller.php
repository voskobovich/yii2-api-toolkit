<?php

namespace voskobovich\api\controllers;

use voskobovich\api\actions\IndexAction;
use voskobovich\api\filters\AccessActionControl;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\filters\Cors;
use yii\rest\OptionsAction;
use yii\rest\ViewAction;


/**
 * Class Controller
 * @package voskobovich\api\controllers
 */
class Controller extends AccessController
{
    /**
     * @var string the permission prefix name. This property must be set.
     * Example:
     * Full action name in access rules "api:user:index"
     *   api:user - this is prefix for current controller
     *   index - action name in your controller
     *
     * More examples:
     *   api:user:index
     *   backend:user:index
     *   frontend:user:index
     *   admin:user:index
     */
    public $permissionPrefix;
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass;
    /**
     * @var string the form class name for Index action. This property must be set.
     */
    public $indexFormClass;
    /**
     * @var string the scenario used for updating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $updateScenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the scenario used for creating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $createScenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index'] = [
            'class' => IndexAction::className(),
            'modelClass' => $this->modelClass,
            'formClass' => $this->indexFormClass,
            'checkAccess' => function ($actionName) {
                AccessActionControl::checkAccess("{$this->permissionPrefix}:index", [
                    'actionName' => $actionName,
                ]);
            }
        ];
        $actions['view'] = [
            'class' => ViewAction::className(),
            'modelClass' => $this->modelClass,
            'checkAccess' => function ($actionName, $model) {
                AccessActionControl::checkAccess("{$this->permissionPrefix}:view", [
                    'actionName' => $actionName,
                    'model' => $model
                ]);
            },
        ];
        $actions['options'] = [
            'class' => OptionsAction::className(),
        ];
        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    /**
     * Behaviors
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['cors'] = [
            'class' => Cors::className(),
        ];
        return $behaviors;
    }
}