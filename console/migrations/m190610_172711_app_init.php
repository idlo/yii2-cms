<?php

use yii\db\Migration;

/**
 * Class m190610_172711_app_init
 */
class m190610_172711_app_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        // Post 文章
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('文章类型'),
            'cid' => $this->integer()->notNull()->comment('自定义分类'),
            'author_id' => $this->integer()->notNull()->comment('作者id'),
            'title' => $this->string()->notNull(),
            'summary' => $this->string(1024)->comment('概要'),
            'source' => $this->string(),
            'image' => $this->string()->comment('主图'),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('状态：1-发布,0-草稿'),
            'tags' => $this->string()->null()->defaultValue('')->comment('标签'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('cid', '{{post}}', 'cid');

        // Article 文章内容
        $this->createTable('{{%article}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('a_post_id', '{{article}}', 'post_id', '{{post}}', 'id');

        // category 文章分类
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey()->unsigned(),
            'parent_id' => $this->integer()->unsigned()->defaultValue(0)->notNull(),
            'name' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'sort' => $this->integer()->unsigned()->defaultValue(0)->notNull(),
            'remark' => $this->string()->defaultValue('')->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->defaultValue(0)->notNull(),
        ], $tableOptions);



        // tag 标签表
        $this->createTable('{{tag}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull()->defaultValue('')->comment('名称'),
            'freq' => $this->integer()->unsigned()->notNull()->defaultValue('0')->comment('引用频率'),
        ]);
        // comment 文章评论
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey()->unsigned(),
            'post_id' => $this->integer()->notNull()->comment('文章id'),
            'user_id' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('用户id,游客为0'),
            'admin_id' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('管理员id,其他人员对其回复为0'),
            'reply_to' => $this->integer()->unsigned()->defaultValue(0)->notNull()->comment('回复的评论id'),
            'nickname' => $this->string()->defaultValue('游客')->notNull()->comment('昵称'),
            'email' => $this->string()->defaultValue('')->notNull()->comment('邮箱'),
            'content' => $this->string()->notNull()->comment('回复内容'),
            'ip' => $this->string()->defaultValue('')->notNull()->comment('ip地址'),
            'status' => $this->smallInteger()->unsigned()->defaultValue(0)->notNull()->comment('状态:0-未审核,1-已通过'),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->defaultValue(0)->notNull(),
        ], $tableOptions);

        $this->addForeignKey('c_post_id', '{{comment}}', 'post_id', '{{post}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%post}}');
        $this->dropTable('{{%article}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%comment}}');
        $this->dropTable('{{%tag}}');
    }

}
