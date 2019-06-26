<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\Html;
use yii\helpers\Url;

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
     * 文章状态 草稿
     */
    const STATUS_DRAFT = 0;
    /**
     * 文章状态 发布
     */
    const STATUS_PUBLISHED = 1;

    /**
     * 文章状态 删除
     */
    const STATUS_TRASH = -1;

    /**
     * Article old tags
     *
     * @var string
     */
    private $_oldTags = '';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'cid', 'author_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['cid', 'title'], 'required'],
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
            'cid' => Yii::t('app', 'Category'),
            'author_id' => Yii::t('app', 'Author ID'),
            'author_name' => Yii::t('app', 'Author Name'),
            'title' => Yii::t('app', 'Title'),
            'summary' => Yii::t('app', 'Summary'),
            'source' => Yii::t('app', 'Source'),
            'image' => Yii::t('app', 'Image'),
            'status' => Yii::t('app', 'Status'),
            'tags' => Yii::t('app', 'Tag'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 文章状态标签
     *
     * @return array
     */
    public function getStatusLabels()
    {
        return [
            self::STATUS_DRAFT => '草稿',
            self::STATUS_PUBLISHED => '发布',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['post_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Admin::className(), ['id' => 'author_id']);
    }

    public function getCategories()
    {
        return Category::find()->all();
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'cid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }

    /**
     * Get begin content
     *
     * @param int $length
     * @return string
     */
    public function getBegin($length = 288)
    {
        return mb_strlen($this->article->content) > $length ? mb_substr($this->article->content, 0, $length) . '...' : $this->article->content;
    }

    /**
     * Get tag links
     *
     * @return array
     */
    public function getTagLinks()
    {
        $links = [];
        foreach (Tag::str2Array($this->tags) as $tag) {
            $links[] = Html::a(Html::encode($tag), ['post/index', 'PostSearch[tags]' => $tag]);
        }

        return $links;
    }

    public function getCommentCount()
    {
        return Comment::find()->where(['post_id' => $this->id])->count('id');
    }
    /**
     * @return string
     */
    public function getUrl()
    {
        return Url::to(['/post/view', 'id' => $this->id]);
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            $this->author_id = Yii::$app->user->id ?: 0;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        Tag::updateFreq($this->_oldTags, $this->tags);
    }

    /**
     * {@inheritDoc}
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->_oldTags = $this->tags;
    }

    /**
     * {@inheritDoc}
     */
    public function afterDelete()
    {
        parent::afterDelete();

        Tag::updateFreq($this->_oldTags, '');
    }
}
