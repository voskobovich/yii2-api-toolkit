<?php

namespace voskobovich\api\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller;
use yii\web\Response;
use yii\rest\Serializer;

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
        'class' => Serializer::class,
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

        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON,
        ];

        $behaviors['authenticator']['optional'] = $this->unsecuredActions;
        $behaviors['authenticator']['authMethods']['queryParam'] = [
            'class' => QueryParamAuth::className(),
            'tokenParam' => 'token',
        ];

        return $behaviors;
    }
}
