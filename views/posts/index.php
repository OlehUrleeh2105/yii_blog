<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap5\Modal;
use yii\widgets\LinkPager;
$this->title = 'Home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php $this->beginBody() ?>   
    <div class="container">
        <div>
            <?php if (!Yii::$app->user->isGuest) : ?>
                <div class="row mb-4">
                    <div class="col-sm">
                        <div class="form-outline">
                            <?= Html::a('+ Add post', ['add'], ['class' => 'btn btn-primary']) ?>
                            <span class="mr-5">
                                <?php if (Yii::$app->user->identity->is_admin) : ?>
                                    <?= Html::a('+ Add Category', ['category'], ['class' => 'btn btn-primary mr-3']) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php $form = ActiveForm::begin(['options' => ['class' => 'mb-3'],  'method' => 'get', 'action' => Url::to(['posts/index'])]); ?>
            <div class="row g-3">
    <div class="col-sm-4">
        <div class="form-outline">
            <?= Html::dropDownList('author', $selectedAuthor, ArrayHelper::map($userOptions, 'id', 'email'), ['prompt' => 'Filter by Author', 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-outline">
            <?= Html::dropDownList('sort', Yii::$app->request->get('sort'), [
                'date' => 'Sort by Date',
                'category' => 'Sort by Category',
            ], ['prompt' => 'Sort By', 'class' => 'form-control']) ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-outline">
            <?= Html::textInput('title', Yii::$app->request->get('title'), ['class' => 'form-control', 'placeholder' => 'Search by Title']) ?>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-outline">
            <?= Html::submitButton('Apply', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>


            <?php ActiveForm::end(); ?>
        </div>

        <div class="row">
    <?php foreach ($dataProvider->getModels() as $post): ?>
                <?php
                $postImageFolder = Yii::getAlias('@webroot/post_imgs/' . $post->id);
                if (is_dir($postImageFolder)) {
                    $images = scandir($postImageFolder);
                    $imagePath = null;

                    foreach ($images as $image) {
                        if (!in_array($image, ['.', '..'])) {
                            $imagePath = Yii::getAlias('@web/post_imgs/' . $post->id . '/' . $image);
                            break;
                        }
                    }
                } else {
                    $imagePath = Yii::getAlias('@web/default_image.jpg');
                }
                ?>
                <div class="col-12 col-md-4">
                    <div class="card mb-4">
                        <div class="card-img-container">
                            <img class="card-img" src="<?= Yii::getAlias($imagePath) ?>" alt="<?= Html::encode($post->title) ?>">
                        </div>
                        <div class="card-body">
                            <?php 
                                $title = Html::encode($post->title);
                                if (mb_strlen($title, 'utf-8') > 20) {
                                    $title = mb_substr($title, 0, 20, 'utf-8') . '...';
                                }
                            ?>
                            <h4 class="card-title"><?= $title ?></h4>
                            <small class="text-muted cat"><?= Html::encode($post->category) ?></small>
                            <?php
                                $content = Html::encode($post->content);
                                if (strlen($content) > 30) {
                                    $content = substr($content, 0, 30) . '...';
                                }
                            ?>
                            <p class="card-text"><?= $content ?></p>
                            <a href="posts/<?= $post->id ?>" class="btn btn-info">Read More</a>
                        </div>
                        <div class="card-footer text-muted d-flex justify-content-between bg-transparent border-top-0">
                            <div class="views"><?= Yii::$app->formatter->asDatetime($post->created_at, 'php:M d, H:iA') ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="text-center">
            <?= LinkPager::widget([
    'pagination' => $dataProvider->getPagination(),
    'options' => ['class' => 'pagination'], 
    'prevPageCssClass' => 'page-item', 
    'nextPageCssClass' => 'page-item',
    'prevPageLabel' => '&laquo;', 
    'nextPageLabel' => '&raquo;', 
    'pageCssClass' => 'page-item', 
    'activePageCssClass' => 'active', 
    'linkOptions' => ['class' => 'page-link'], 
    'disabledListItemSubTagOptions' => ['class' => 'page-link'],
]) ?>

</div>
        </div>
    </div>


<?php $this->endBody() ?>
</body>
</html>
