<?php

use yii\helpers\Html;

/*  @var $model common\models\Post */
?>

<div>
    <div class="title mb-3">
        <h4><a href="<?= $model->url ?>"><?= Html::encode($model->title)?></a></h4>
    </div>

    <div class="author mb-3">
        <i class="glyphicon glyphicon-time"><?= date('Y-m-d H:i:s', $model->created_at);?></i>
        <i class="glyphicon glyphicon-user"><?= Html::encode($model->author->username)?></i>
    </div>

    <br>

    <div class="content mb-3">
        <?= Html::encode($model->begin)?>
    </div>

    <br>

    <div class="nav">
        <span class="glyphicon glyphicon-tag" aria-hidden="true"></span>
        <?= implode(',', $model->tagLinks)?>
        <br>
        <span class="glyphicon glyphicon-comment">评论(<?= $model->commentCount?>)</span>
        <span class="glyphicon glyphicon-edit">最后修改于：<?= date('Y-m-d H:i:s', $model->updated_at);?></span>
    </div>
</div>
