<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;

    $this->title = 'Login';
    $this->params['breadcrumbs'][] = $this->title;

?>


<section class="vh-50">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                <?php $form = ActiveForm::begin(); ?>
                <form>
                    <div class="form-outline mb-4">
                            <?= $form->field($model, 'email')->textInput([
                                'autofocus' => true,
                                'class' => 'form-control form-control-lg',
                                'type' => 'email', 
                                'placeholder' => 'Enter a valid email address', 
                            ]) ?>
                    </div>

                    <div class="form-outline mb-3">
                            <?= $form->field($model, 'password')->passwordInput([
                                'class' => 'form-control form-control-lg',
                                'type' => 'password', 
                                'placeholder' => 'Enter password', 
                            ]) ?>
                    </div>

                    <div class="text-center text-lg-start pt-2">
                        <?= Html::submitButton('Login', [
                            'class' => 'btn btn-primary btn-lg', 
                            'name' => 'login-button',
                            'style' => 'padding-left: 2.5rem; padding-right: 2.5rem;',
                        ]); ?>
                        <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <?= Html::a('Register', ['site/registration'], ['class' => 'link-danger', 'style' => 'text-decoration:none;']) ?></p>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>