<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Enter Category';

$fieldConfig = [
    'template' => "{input}\n{label}\n{error}",
    'inputOptions' => ['class' => 'form-control'],
    'options' => ['class' => 'user-box'],
    'labelOptions' => ['class' => 'label-class'],
];
?>

<style>
    body {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: dodgerblue;
        font-family: "Sansita Swashed", cursive;
    }

    body, input, button {
        font-family: sans-serif;
    }

    .center {
        position: relative;
        padding: 50px 50px;
        background: #fff;
        border-radius: 10px;
    }

    .center h1 {
        font-size: 2em;
        border-left: 5px solid dodgerblue;
        padding: 10px;
        color: #000;
        letter-spacing: 5px;
        margin-bottom: 60px;
        font-weight: bold;
        padding-left: 10px;
    }

    .center .inputbox {
        position: relative;
        width: 300px;
        height: 50px;
        margin-bottom: 50px;
    }

    .center .inputbox input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        border: 2px solid #000;
        outline: none;
        background: none;
        padding: 10px;
        border-radius: 10px;
        font-size: 1.2em;
    }

    .center .inputbox:last-child {
        margin-bottom: 0;
    }

    .center .inputbox span {
        position: absolute;
        top: 14px;
        left: 20px;
        font-size: 1em;
        transition: 0.6s;
        font-family: sans-serif;
    }

    .center .inputbox input:focus ~ span,
    .center .inputbox input:valid ~ span {
        transform: translateX(-13px) translateY(-35px);
        font-size: 1em;
    }

    button, [type="submit"] {
        background-color:  #fa6400;
        border-radius: 8px;
        border-style: none;
        box-sizing: border-box;
        color: #FFFFFF;
        cursor: pointer;
        display: inline-block;
        font-size: 14px;
        font-weight: 500;
        height: 40px;
        line-height: 20px;
        list-style: none;
        margin: 0;
        outline: none;
        padding: 10px 16px;
        position: relative;
        text-align: center;
        text-decoration: none;
        transition: color 100ms;
        vertical-align: baseline;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }
</style>

<div class="center">
    <h1>Enter category</h1>
    <?php
    $form = ActiveForm::begin([
        'options' => ['class' => 'login-box'],
        'fieldConfig' => $fieldConfig,
    ]);
    ?>
    <div class="inputbox">
        <?= $form->field($category, 'content')->textInput(['autofocus' => true])->label(false); ?>
    </div>
    <div class="inputbox">
        <?= Html::submitButton('Submit', ['name' => 'category-button']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
