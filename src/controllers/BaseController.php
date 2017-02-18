<?php

namespace voskobovich\api\controllers;

use yii\rest\Controller;
use voskobovich\api\filters\auth\QueryParamAuth;
use yii\filters\Cors;

/**
 * Class BaseController.
 */
class BaseController extends Controller
{
    /**
     * The list of actions not needing token protection.
     *
     * @var array
     */
    public $unsecuredActions = [];

    /**
     * @var string|array the configuration for creating the serializer that formats the response data
     */
    public $serializer = [
        'class' => 'tuyakhov\jsonapi\Serializer',
        'pluralize' => false,
    ];

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
}
