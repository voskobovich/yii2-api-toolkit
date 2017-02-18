<?php

namespace voskobovich\api\actions;

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
            $appId = Yii::$app->id;

            $allowAccess = Yii::$app->user->can(
                "{$appId}:{$this->controller->id}:{$this->id}",
                array_merge(
                    ['actionName' => $this->id],
                    $params
                )
            );

            if (!$allowAccess) {
                throw new UnauthorizedHttpException('You are requesting with an invalid credential.');
            }
        } else {
            throw new UnauthorizedHttpException('You are requesting with an invalid credential.');
        }
    }
}
