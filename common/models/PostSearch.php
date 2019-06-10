<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post;
use yii\helpers\ArrayHelper;

/**
 * PostSearch represents the model behind the search form of `common\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * {@inheritDoc}
     * @return array
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), ['author_name']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'cid', 'author_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'summary', 'source', 'author_name', 'tags'], 'safe'],
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
        $query = Post::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => 'id DESC',
                'attributes' => ['id', 'status', 'created_at'],
            ],]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'post.id' => $this->id,
            'cid' => $this->cid,
            'type' => $this->type,
            'post.status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        $query->innerJoin(Admin::tableName(), 'post.author_id = admin.id');

        $query->andFilterWhere(['like', 'admin.username', $this->author_name]);


        return $dataProvider;
    }
}
