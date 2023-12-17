<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Posts extends ActiveRecord
{
    public $imageFiles;

    public static function tableName(){
        return 'posts';
    }

    public static function getCategoryList()
    {
        $categories = Category::find()->all();
        return ArrayHelper::map($categories, 'id', 'content');
    }

    public function rules(){
        return [
            [['imageFiles'], 'file', 'extensions' => 'png, jpg, jpeg, gif, webp', 'maxFiles' => 3],
            [['title', 'category', 'content'], 'required'],
            [['title', 'category', 'content'], 'trim']
        ];   
    }
}
