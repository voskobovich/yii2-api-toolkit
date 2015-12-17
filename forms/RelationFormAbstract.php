<?php

namespace voskobovich\rest\base\forms;

use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Class RelationFormAbstract
 * @package voskobovich\rest\base\forms
 */
abstract class RelationFormAbstract extends Model
{
    /**
     * Query building
     * @param ActiveRecord $model
     * @return ActiveQuery
     */
    abstract public function buildQuery(ActiveRecord $model);
}