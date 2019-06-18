<?php

namespace backend\controllers;

use Yii;
use Exception;
use Throwable;
use common\models\Post;
use yii\web\Controller;
use common\models\Article;
use yii\filters\VerbFilter;
use common\models\PostSearch;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('createPost')) {
            throw new ForbiddenHttpException('暂无权限执行此操作');
        }

        $model = new Post();
        $article = new Article();

        if ($model->load(Yii::$app->request->post()) && $article->load(Yii::$app->request->post())) {
            $transaction = Post::getDb()->beginTransaction();
            try {
                $model->save();
                $article->post_id = $model->id;
                $article->save();
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $e) {
                $transaction->rollBack();
            } catch (Throwable $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'article' => $article,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('updatePost')) {
            throw new ForbiddenHttpException('暂无权限执行此操作');
        }
        $model = $this->findModel($id);
        $article = Article::findOne(['post_id' => $model->id]);

        if ($model->load(Yii::$app->request->post()) && $article->load(Yii::$app->request->post())) {
            Post::getDb()->transaction(function ($db) use ($model, $article) {
                $model->save();
                $article->save();
            });
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'article' => $article,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     * @throws Throwable
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('deletePost')) {
            throw new ForbiddenHttpException('暂无权限执行此操作');
        }
        Post::getDb()->transaction(function ($db) use ($id) {
            $model = $this->findModel($id);
            Article::findOne(['post_id' => $model->id])->delete();
            $model->delete();
        });

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
