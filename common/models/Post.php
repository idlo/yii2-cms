<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $type 文章类型
 * @property int $cid 自定义分类
 * @property int $author_id 作者id
 * @property string $title
 * @property string $summary 概要
 * @property string $source
 * @property string $image 主图
 * @property int $status 状态：1-发布,0-草稿
 * @property string $tags 标签
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Article[] $articles
 * @property Comment[] $comments
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'cid', 'author_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['cid', 'author_id', 'title', 'created_at', 'updated_at'], 'required'],
            [['title', 'source', 'image', 'tags'], 'string', 'max' => 255],
            [['summary'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'cid' => Yii::t('app', 'Cid'),
            'author_id' => Yii::t('app', 'Author ID'),
            'title' => Yii::t('app', 'Title'),
            'summary' => Yii::t('app', 'Summary'),
            'source' => Yii::t('app', 'Source'),
            'image' => Yii::t('app', 'Image'),
            'status' => Yii::t('app', 'Status'),
            'tags' => Yii::t('app', 'Tags'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }
}
