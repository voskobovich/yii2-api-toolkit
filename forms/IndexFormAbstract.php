<?php

namespace voskobovich\rest\base\forms;

use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Class IndexFormAbstract
 * @package voskobovich\rest\base\forms
 */
abstract class IndexFormAbstract extends Model
{
    /**
     * Query building
     * @param ActiveRecord $model
     * @return ActiveQuery
     */
    abstract public function buildQuery(ActiveRecord $model);
}