<?php

namespace voskobovich\api\controllers;

use yii\rest\Controller;
use voskobovich\api\filters\auth\QueryParamAuth;

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
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
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

        return $behaviors;
    }
}
