<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Add post';

?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'Addpost', 'enctype' => 'multipart/form-data']]) ?>
<div class="row mb-2">
    <div class="col">
        <div class="form-outline">
            <?= $form->field($model, 'title')->textInput(['placeholder' => 'Enter the post title'])->label(false) ?>
        </div>
    </div>
    <div class="col">
        <div class="form-outline">
            <?= $form->field($model, 'category', ['options' => ['class' => 'form-group'], 'template' => '{input}'])->dropDownList(
                $categories,
                ['prompt' => 'Select Category']
            ) ?>
        </div>
    </div>
</div>

<?= $form->field($model, 'content')->textarea(['rows' => 5, 'placeholder' => 'Enter post content' ])->label(false) ?>

<?= $form->field($model, 'imageFiles[]')->fileInput([
    'multiple' => true,
    'class' => 'form-control form-control-sm',
    'inputOptions' => [
        'type' => 'file',
    ],
])->label('Upload post images') ?>

<?= Html::submitButton('Save', ['class' => 'btn btn-success btn-lg']) ?>
<?php ActiveForm::end() ?>