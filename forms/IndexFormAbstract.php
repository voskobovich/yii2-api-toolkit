<?php

namespace voskobovich\rest\base\forms;

use yii\base\Model;
use yii\db\ActiveQuery;


/**
 * Class IndexFormAbstract
 * @package voskobovich\rest\base\forms
 */
abstract class IndexFormAbstract extends Model
{
    /**
     * ActiveQuery Object
     * @var ActiveQuery
     */
    public $query;

    /**
     * Query building
     * @return ActiveQuery
     */
    abstract public function buildQuery();
}