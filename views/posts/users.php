<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-dark'],
    'summary' => false,
    'columns' => [
        'id',
        'name',
        'surname',
        'email',
        'password',
        [
            'class' => ActionColumn::className(),
            'template' => '{edit} {delete}',
            'buttons' => [
                'edit' => function ($url, $model, $key) {
                    return \yii\helpers\Html::a(
                        '<span class="btn btn-primary">Edit</span>',
                        ['posts/edit-user', 'id' => $model->id],
                        [
                            'title' => 'Edit',
                        ]
                    );
                },
                'delete' => function ($url, $model, $key) {
                    return \yii\helpers\Html::a(
                        '<span class="btn btn-danger">X</span>',
                        ['posts/delete-user', 'id' => $model->id],
                        [
                            'title' => 'Delete',
                            'data-confirm' => 'Are you sure you want to delete this user?',
                            'data-method' => 'post',
                        ]
                    );
                },
            ],
        ],        
    ],
    'pager' => [
        'linkOptions' => ['class' => 'btn btn-primary', 'style' => 'margin-left: 5px;'],
        'maxButtonCount' => 6,
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'disabledPageCssClass' => 'btn btn-outline-danger',
        'linkContainerOptions' => ['style' => 'margin-left: 8px;'],
    ],
]);

?>