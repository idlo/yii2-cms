<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Admins');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Admin'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
            ],

            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{reset-password}{privilege}',
                'buttons' => [
                    'reset-password' => function ($url) {
                        $option = [
                            'title' => Yii::t('app', 'Reset Password'),
                            'aria-label' => Yii::t('app', 'Reset Password'),
                            'data-method' => 'POST',
                            'data-pjx' => 0,
                        ];
                        return Html::a('<span class="glyphicon glyphicon-lock"></span>', $url, $option);
                    },
                    'privilege' => function ($url) {
                        $option = [
                            'title' => Yii::t('app', 'Privilege'),
                            'aria-label' => Yii::t('app', 'Privilege'),
                            'data-method' => 'POST',
                            'data-pjx' => 0,
                        ];
                        return Html::a('<span class="glyphicon glyphicon-user"></span>', $url, $option);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
