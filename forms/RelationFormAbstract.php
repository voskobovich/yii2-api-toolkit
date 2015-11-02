<?php

namespace voskobovich\rest\base\forms;

use yii\base\Model;


/**
 * Class RelationFormAbstract
 * @package voskobovich\rest\base\forms
 */
abstract class RelationFormAbstract extends Model
{
    /**
     * Model object
     * @var Model
     */
    public $model;

    /**
     * Query building
     * @return \yii\db\ActiveQuery
     */
    abstract public function buildQuery();
}