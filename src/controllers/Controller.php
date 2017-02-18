<?php

namespace voskobovich\api\controllers;

use voskobovich\api\actions\IndexAction;
use voskobovich\api\filters\auth\QueryParamAuth;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\filters\Cors;
use yii\rest\OptionsAction;
use yii\rest\ViewAction;

/**
 * Class Controller.
 */
class Controller extends \yii\rest\Controller
{
    /**
     * The list of actions not needing token protection.
     *
     * @var array
     */
    public $unsecuredActions = [];

    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass;

    /**
     * @var string the form class name for Index action. This property must be set.
     */
    public $indexFormClass;

    /**
     * @var string the scenario used for updating a model
     *
     * @see \yii\base\Model::scenarios()
     */
    public $updateScenario = Model::SCENARIO_DEFAULT;

    /**
     * @var string the scenario used for creating a model
     *
     * @see \yii\base\Model::scenarios()
     */
    public $createScenario = Model::SCENARIO_DEFAULT;

    /**
     * @var string|array the configuration for creating the serializer that formats the response data
     */
    public $serializer = [
        'class' => 'tuyakhov\jsonapi\Serializer',
        'pluralize' => false,
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
    }

    /**
     * Behaviors.
     *
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['optional'] = $this->unsecuredActions;
        $behaviors['authenticator']['authMethods'][] = [
            'class' => QueryParamAuth::className(),
            'tokenParam' => 'token',
        ];
        $behaviors['cors'] = [
            'class' => Cors::className(),
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index'] = [
            'class' => IndexAction::className(),
            'modelClass' => $this->modelClass,
            'formClass' => $this->indexFormClass,
        ];
        $actions['view'] = [
            'class' => ViewAction::className(),
            'modelClass' => $this->modelClass,
        ];
        $actions['options'] = [
            'class' => OptionsAction::className(),
        ];

        return $actions;
    }
}
