<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Comment;
use yii\helpers\ArrayHelper;

/**
 * CommentSearch represents the model behind the search form of `common\models\Comment`.
 */
class CommentSearch extends Comment
{
    /**
     * {@inheritDoc}
     * @return array
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), ['user_name', 'post_title']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'post_id', 'user_id', 'admin_id', 'reply_to', 'status', 'created_at', 'updated_at'], 'integer'],
            [['nickname', 'user_name', 'post_title', 'email', 'content', 'ip'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Comment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC,
                    'id' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'post.status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content]);

        $query->innerJoin(Admin::tableName(), 'admin.id = comment.user_id');
        $query->andFilterWhere(['like', 'admin.username', $this->user_name]);

        $query->innerJoin(Post::tableName(), 'post.id = comment.post_id');
        $query->andFilterWhere(['like', 'post.title', $this->post_title]);

        return $dataProvider;
    }
}
