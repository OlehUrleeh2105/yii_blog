<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Edit Post: ';

?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'Addpost', 'enctype' => 'multipart/form-data']]) ?>
<div class="row mb-2">
    <div class="col">
        <div class="form-outline">
            <?= $form->field($post, 'title')->textInput(['placeholder' => 'Enter the post title'])->label(false) ?>
        </div>
    </div>
    <div class="col">
        <div class="form-outline">
            <?= $form->field($post, 'category', ['options' => ['class' => 'form-group'], 'template' => '{input}'])->dropDownList(
                $categories,
                ['prompt' => 'Select Category']
            ) ?>
        </div>
    </div>
</div>

<?= $form->field($post, 'content')->textarea(['rows' => 5, 'placeholder' => 'Enter post content' ])->label(false) ?>

<?= Html::submitButton('Update', ['class' => 'btn btn-success btn-lg']) ?>
<?php ActiveForm::end() ?>