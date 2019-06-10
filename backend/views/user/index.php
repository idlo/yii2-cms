<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusLabels[$model->status];
                },
                'filter' => $searchModel->statusLabels,
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{disable}',
                'buttons' => [
                    'disable' => function ($url) {
                        $option = [
                            'title' => Yii::t('app', 'Disable'),
                            'aria-label' => Yii::t('app', 'Disable'),
                            'data-confirm' => Yii::t('app', 'Are you sure you want to disable this user?'),
                            'data-method' => 'POST',
                            'data-pjx' => 0,
                        ];

                        return Html::a('<span class="glyphicon glyphicon-ban-circle"></span>', $url, $option);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
