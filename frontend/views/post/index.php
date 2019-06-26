<?php

use common\models\Tag;
use yii\helpers\Url;
use yii\widgets\ListView;
use frontend\widgets\TagCloudWidget;
use frontend\widgets\RecentReplyWidget;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $tags common\models\Tag */
/* @var $comments common\models\comment*/

$this->title = Yii::t('app', 'Post');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <div class="col-lg-9">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item',
            'layout' => '{items}{pager}',
            'pager' => [
                'maxButtonCount' => 5,
                'firstPageLabel' => Yii::t('app', 'First Page'),
                'prevPageLabel' => Yii::t('app', 'Prev Page'),
                'nextPageLabel' => Yii::t('app', 'Next Page'),
                'lastPageLabel' => Yii::t('app', 'Last Page'),
            ],
        ]); ?>
    </div>

    <div class="col-lg-3">
        <div class="post-search">
            <ul class="list-group">
                <li class="list-group-item"><span class="glyphicon glyphicon-search" aria-hidden="true">查找文章</span></li>
                <li class="list-group-item">
                    <form class="form-inline" action="<?= Url::to(['post/index'])?>" id="w0" method="get">
                        <div class="form-group">
                            <label class="sr-only" for="w0input">名称</label>
                            <input type="text" class="form-control" name="PostSearch[title]" id="w0input" placeholder="请输入文章标题">
                        </div>
                        <button type="submit" class="btn btn-default">提交</button>
                    </form>
                </li>
            </ul>
        </div>
        <div class="tag-cloud">
            <ul class="list-group">
                <li class="list-group-item"><span class="glyphicon glyphicon-tags" aria-hidden="true">标签云</span></li>
                <li class="list-group-item">
                    <?= TagCloudWidget::widget(['tags' => $tags]);?>
                </li>
            </ul>
        </div>
        <div class="comment">
            <ul class="list-group">
                <li class="list-group-item"><span class="glyphicon glyphicon-comment" aria-hidden="true">最新回复</span></li>
                <li class="list-group-item">
                    <?= RecentReplyWidget::widget(['recentComments' => $comments])?>
                </li>
            </ul>
        </div>
    </div>

</div>
