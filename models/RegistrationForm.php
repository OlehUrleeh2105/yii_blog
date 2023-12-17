<?php
// models/RegistrationForm.php

namespace app\models;

use Yii;
use yii\base\Model;

class RegistrationForm extends Model
{
    public $email;
    public $password;
    public $name;
    public $surname;

    public function rules()
    {
        return [
            [['email', 'password', 'name', 'surname'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
            ['password', 'string', 'min' => 6],
        ];
    }
}
