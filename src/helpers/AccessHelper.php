<?php

namespace voskobovich\api\helpers;

use Yii;
use yii\web\UnauthorizedHttpException;

/**
 * Class AccessHelper.
 */
class AccessHelper
{
    /**
     * Check access for controller action.
     *
     * @param string $permissionName
     * @param array  $params
     *
     * @throws UnauthorizedHttpException
     */
    public static function check($permissionName, $params = [])
    {
        if (false === Yii::$app->user->can($permissionName, $params)) {
            throw new UnauthorizedHttpException('You are requesting with an invalid credential.');
        }
    }
}
