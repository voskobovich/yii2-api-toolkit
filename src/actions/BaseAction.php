<?php

namespace voskobovich\api\actions;

use voskobovich\api\helpers\AccessHelper;
use Yii;
use yii\rest\Action;
use yii\web\UnauthorizedHttpException;

/**
 * Class BaseAction.
 */
abstract class BaseAction extends Action
{
    /**
     * Access control for action.
     *
     * @param array $params
     *
     * @throws UnauthorizedHttpException
     */
    public function runAccessControl($params = [])
    {
        if (is_callable($this->checkAccess)) {
            call_user_func($this->checkAccess, $this->id);
        } elseif (!empty($this->controller)) {
            $app = Yii::$app;
            $permissionName = "{$app->id}:{$this->controller->id}:{$this->id}";

            AccessHelper::check(
                $permissionName,
                array_merge(
                    ['action' => $this],
                    $params
                )
            );
        } else {
            throw new UnauthorizedHttpException('You are requesting with an invalid credential.');
        }
    }
}
