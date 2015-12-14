<?php

namespace voskobovich\rest\filters;

use Yii;
use yii\web\UnauthorizedHttpException;


/**
 * Class ActionAccessControl
 * @package voskobovich\rest\base\filters
 */
class ActionAccessControl
{
    /**
     * @param $permName
     * @param $params
     * @throws UnauthorizedHttpException
     */
    public static function checkAccess($permName, $params)
    {
        if (!Yii::$app->user->can($permName, $params)) {
            throw new UnauthorizedHttpException('You are requesting with an invalid credential.');
        }
    }
}