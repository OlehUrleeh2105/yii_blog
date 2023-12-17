<?php

namespace app\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string', 'max' => 35],
        ];
    }
}
