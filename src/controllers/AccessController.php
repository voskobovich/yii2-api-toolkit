<?php

namespace voskobovich\api\controllers;

use voskobovich\api\filters\auth\QueryParamAuth;
use Yii;
use yii\rest\Controller;


/**
 * Class AccessController
 * @package voskobovich\api\controllers
 */
class AccessController extends Controller
{
    /**
     * The list of actions not needing token protection.
     * @var array
     */
    public $unsecuredActions = [];

    /**
     * Behaviors
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'authMethods' => [
                [
                    'class' => QueryParamAuth::className(),
                    'exceptActions' => $this->unsecuredActions,
                    'tokenParam' => 'token'
                ]
            ]
        ];
        return $behaviors;
    }
}
