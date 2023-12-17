<?php 

use coderius\swiperslider\SwiperSlider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\FileHelper;
use app\models\PostLikes;
use app\models\User;

$postImageFolder = Yii::getAlias('@webroot/post_imgs/' . $post->id);
if (is_dir($postImageFolder)) {
    $images = scandir($postImageFolder);
    $imageTags = []; 
    foreach ($images as $image) {
        if (!in_array($image, ['.', '..']) && is_file($postImageFolder . DIRECTORY_SEPARATOR . $image)) {
            $imageTags[] = Html::img('@web/post_imgs/' . $post->id . '/' . $image, ['alt' => 'Image', 'class' => 'responsive']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>    
        .form-group input,.form-group textarea{
            background-color: black;
            border: 1px solid rgba(16, 46, 46, 1);
            border-radius: 12px;
        }

        .fform{
            border: 1px solid rgba(16, 46, 46, 1);
            background-color: rgba(16, 46, 46, 0.973);
            border-radius: 5px;
            padding: 20px;
        }

        .dot {
            background-color: #fc0303;
            border-radius: 50%;
        }
    </style>

    <title>Document</title>
</head>
<body>
    <div class="clearfix">
        <div class="left-side">
            <?php
                echo \coderius\swiperslider\SwiperSlider::widget([
                    'slides' => $imageTags,
                    'clientOptions' => [
                        'spaceBetween'=> 30,
                        'centeredSlides'=> true,
                        'pagination' => [
                            'clickable' => true,
                            ],
                            "scrollbar" => [
                                "el" => \coderius\swiperslider\SwiperSlider::getItemCssClass(SwiperSlider::SCROLLBAR),
                                "hide" => true,
                            ],
                    ],
                    'options' => [
                        'styles' => [
                        \coderius\swiperslider\SwiperSlider::CONTAINER => ["height" => "400px"],
                        \coderius\swiperslider\SwiperSlider::CONTAINER => ["width" => "100%"], 
                        \coderius\swiperslider\SwiperSlider::SLIDE => ["text-align" => "center"],
                        ],
                    ],
                ]);
            ?>
        </div>

        <div class="right-side mt-4">
            <h1><?= Html::encode($post->title) ?></h1>
            <div class="d-flex justify-content-between">
                <div><p class="fs-4">Category: <?= $post->category ?></p></div>
                <div class="mt-2">Posted at: <?= DateTime::createFromFormat('Y-m-d H:i:s', $post->created_at)->format('d F, Y') ?></div>
            </div>

            <div class="mb-4">
                <?= Html::encode($post->content) ?>
            </div>
        
            <?php
                if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->id === $post->created_by || Yii::$app->user->identity->is_admin)) {
                    echo Html::a('Edit', ['edit', 'id' => $post->id], ['class' => 'btn btn-primary']);

                    echo '<div class="mb-2"></div>';

                    echo  Html::a(
                        'Delete',
                        Url::to(['posts/delete', 'id' => $post->id]),
                        [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this post?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            ?>
        </div>

            <div class="row">
                <div class="col-md-10 col-lg-9 col-12 pb-1">
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="d-flex align-items-center">
                        <p class="h5 mt-1 mr-2">Like (  <?= PostLikes::find()->where(['post_id' => $post->id, 'is_liked' => 1])->count(); ?>  ): </p>
                            <?php
                                $isLiked = PostLikes::findOne(['user_id' => Yii::$app->user->id, 'post_id' => $post->id]);
                            ?>
                            <div class="<?php if($isLiked && $isLiked->is_liked) { echo 'bg-danger'; }?> rounded-circle d-flex align-items-center justify-content-center shadow-sm mb-2 ml-1" style="width: 30px; height: 30px;">
                                <?= Html::a(
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi" viewBox="0 0 16 16">
                                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                    </svg>',
                                    ['posts/like', 'id' => $post->id],
                                    ['class' => 'text-dark mt-1']
                                ) ?>
                            </div>
                        </div>
                        <h1 id="users_comments">Comments:</h1>
                    </div>

                    <?php foreach ($comments as $comment): ?>
                        <div class="card mt-3" style="border: 1px solid rgba(16, 46, 46, 1); background-color: rgba(16, 46, 46, 0.973);">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <?php
                                        $user = User::findOne($comment->user_id);
                                    ?>
                                    <div class="h4 text-white"><?= $user->name ?> <?= $user->surname ?></div>
                                    <div class="text-secondary"><?= DateTime::createFromFormat('Y-m-d H:i:s', $comment->created_at)->format('d F, Y') ?></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-12">
                                    <blockquote class="blockquote mb-0 text-white">
                                        <p><?= Html::encode($comment->content) ?></p>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="mt-3">
                        <?php echo LinkPager::widget([
                            'pagination' => $pagination,
                            'linkOptions' => ['class' => 'btn btn-primary', 'style' => 'margin-left: 5px;'],
                            'maxButtonCount' => 6,
                            'prevPageLabel' => '<',
                            'nextPageLabel' => '>',
                            'disabledPageCssClass' => 'btn btn-outline-danger',
                            'linkContainerOptions' => ['style' => 'margin-left: 8px;'],
                        ]) ?>
                    </div>
                </div>

                <?php $form = ActiveForm::begin([
                    'action' => ['posts/details', 'id' => $post->id],
                    'options' => ['class' => 'fform'],
                ]); ?>
                    <div class="form-group">
                        <h2 class="text-white">Leave a Comment</h2>
                        <label for="message" class="text-white">Message</label>
                        <?= $form->field($commentModel, 'content')->textarea([
                            'rows' => 4,
                            'class' => 'form-control', 
                            'style' => 'background-color: gray; color: white;',
                        ])->label(false) ?>
                        <div class="form-group text-center">
                            <?= Html::submitButton('Post Comment', ['class' => 'btn btn-light font-weight-bold']) ?>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

    </div>
</body>
</html>