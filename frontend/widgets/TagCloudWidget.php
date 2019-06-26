<?php


namespace frontend\widgets;

use Yii;
use yii\base\Widget;

class TagCloudWidget extends Widget
{
    public $tags;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $styles = [
            '6' => 'danger',
            '5' => 'info',
            '4' => 'warning',
            '3' => 'primary',
            '2' => 'success',
        ];

        $str = '';
        foreach ($this->tags as $tag => $weight) {
            $str .= '<a href="' . Yii::$app->urlManager->createUrl((['post/index', 'PostSearch[title]' => $tag])) . '">'
                . '<h' . $weight . ' style="display:inline-block"><span class="label label-' . $styles[$weight] . '">' . $tag . '</span></h' . $weight . '></a>';
        }

        return $str;
    }
}