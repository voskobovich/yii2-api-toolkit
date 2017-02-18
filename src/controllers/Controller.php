<?php

namespace voskobovich\api\controllers;

use voskobovich\api\actions\IndexAction;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\rest\OptionsAction;
use yii\rest\ViewAction;

/**
 * Class Controller.
 */
class Controller extends BaseController
{
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass;

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
