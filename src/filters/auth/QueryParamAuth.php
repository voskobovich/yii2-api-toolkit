<?php

namespace voskobovich\api\filters\auth;

use Yii;
use yii\web\UnauthorizedHttpException;


/**
 * Class QueryParamAuth
 * @package api\filters\auth
 */
class QueryParamAuth extends \yii\filters\auth\QueryParamAuth
{
    /**
     * Actions ids
     * @var array
     */
    public $exceptActions = [];

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, $this->exceptActions)) {
            return true;
        }

        $response = $this->response ?: Yii::$app->getResponse();

        $this->authenticate(
            $this->user ?: Yii::$app->getUser(),
            $this->request ?: Yii::$app->getRequest(),
            $response
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $accessToken = $request->get($this->tokenParam);

        if (!is_string($accessToken)) {
            throw new UnauthorizedHttpException('You are requesting with an invalid credential.');
        }

        $user->loginByAccessToken($accessToken, get_class($this));
    }
}
