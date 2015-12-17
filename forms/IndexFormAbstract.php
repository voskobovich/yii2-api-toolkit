<?php

namespace voskobovich\rest\base\forms;

use yii\base\Model;


/**
 * Class IndexFormAbstract
 * @package voskobovich\rest\base\forms
 */
abstract class IndexFormAbstract extends Model
{
    /**
     * Query building
     * @param \yii\db\ActiveRecord $model
     * @return \yii\db\ActiveQuery
     */
    abstract public function buildQuery($model);
}