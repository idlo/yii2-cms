<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $post_id 文章id
 * @property int $user_id 用户id,游客为0
 * @property int $admin_id 管理员id,其他人员对其回复为0
 * @property int $reply_to 回复的评论id
 * @property string $nickname 昵称
 * @property string $email 邮箱
 * @property string $content 回复内容
 * @property string $ip ip地址
 * @property int $status 状态:0-未审核,1-已通过
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Post $post
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'content', 'created_at'], 'required'],
            [['post_id', 'user_id', 'admin_id', 'reply_to', 'status', 'created_at', 'updated_at'], 'integer'],
            [['nickname', 'email', 'content', 'ip'], 'string', 'max' => 255],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'post_id' => Yii::t('app', 'Post ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'admin_id' => Yii::t('app', 'Admin ID'),
            'reply_to' => Yii::t('app', 'Reply To'),
            'nickname' => Yii::t('app', 'Nickname'),
            'email' => Yii::t('app', 'Email'),
            'content' => Yii::t('app', 'Content'),
            'ip' => Yii::t('app', 'Ip'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
}
