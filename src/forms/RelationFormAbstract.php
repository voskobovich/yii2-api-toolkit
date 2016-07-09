<?php

namespace voskobovich\api\forms;

use yii\base\Model;


/**
 * Class RelationFormAbstract
 * @package voskobovich\api\forms
 */
abstract class RelationFormAbstract extends Model
{
    /**
     * Query building
     * @param \yii\db\ActiveRecord $model
     * @return \yii\db\ActiveQuery
     */
    abstract public function buildQuery($model);
}