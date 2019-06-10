<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Comments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p></p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'content',
                'value' => 'subContent',
                'options' => ['style' => 'width:12px']
            ],
            [
                'attribute' => 'user_name',
                'value' => 'user.username',
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusLabels[$model->status];
                },
                'filter' => $searchModel->statusLabels,
                'contentOptions' => function ($model) {
                    return $model->status == 0 ? ['class' => 'bg-danger'] : [];
                },
            ],
            [
                'attribute' => 'post_title',
                'value' => 'post.title',
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}{approve}',
                'buttons' => [
                    'approve' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('app', 'Approve'),
                            'aria-label' => Yii::t('app', 'Approve'),
                            'data-confirm' => Yii::t('app', 'Are you sure the approval is passed?'),
                            'date-method' => 'POST',
                            'data-pjx' => 0,
                        ];

                        return Html::a('<span class="glyphicon glyphicon-check"></span>', $url, $options);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
