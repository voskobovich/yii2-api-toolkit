<?php

namespace voskobovich\api\filters\auth;

use Yii;
use yii\web\UnauthorizedHttpException;


/**
 * Class QueryParamAuth
 * @package voskobovich\api\filters\auth
 */
class QueryParamAuth extends \yii\filters\auth\QueryParamAuth
{
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
