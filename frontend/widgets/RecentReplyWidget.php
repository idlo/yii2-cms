<?php


namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class RecentReplyWidget extends Widget
{
    public $recentComments;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $commentStr = '';

        foreach ($this->recentComments as $comment) {
            $commentStr = '<div class="post">' .
                '<div class="title">' .
                '<p style="font-color:#777;font-style: italic">' .
                nl2br($comment->content)
                . '</p>' .
                '<p style="font-size: 8pt;color:blue;">《<a href="' . $comment->post->url . '">' . Html::encode($comment->post->title) . '</a>》</p>' .
                '<hr></div></div>';
        }

        return $commentStr;
    }
}