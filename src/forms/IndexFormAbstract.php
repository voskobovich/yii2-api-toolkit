<?php

namespace voskobovich\api\forms;

use yii\base\Model;

/**
 * Class IndexFormAbstract.
 */
abstract class IndexFormAbstract extends Model
{
    /**
     * Query building.
     *
     * @param \yii\db\ActiveRecordInterface $model
     *
     * @return \yii\db\ActiveQuery
     */
    abstract public function buildQuery($model);
}
