<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Admin;
use common\models\AuthItem;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use backend\models\SignupForm;
use common\models\AdminSearch;
use common\models\AuthAssignment;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use backend\models\ResetPasswordForm;

/**
 * AdminController implements the CRUD actions for Admin model.
 */
class AdminController extends Controller
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
     * Lists all Admin models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Admin model.
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
     * Creates a new Admin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('createAdmin')) {
            throw new ForbiddenHttpException('暂无权限执行此操作');
        }

        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $admin = $model->signup()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Registration Success!'));
            $this->redirect(['view', 'id' => $admin->id]);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('updateAdmin')) {
            throw new ForbiddenHttpException('暂无权限执行此操作');
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Reset Admin Password
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionResetPassword($id)
    {
        if (!Yii::$app->user->can('resetAdminPassword')) {
            throw new ForbiddenHttpException('暂无权限执行此操作');
        }

        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->resetPassword($id)) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Reset Password Success!'));
            $this->redirect(['index']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Privilege Admin
     *
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionPrivilege($id)
    {
        if (!Yii::$app->user->can('privilegeAdmin')) {
            throw new ForbiddenHttpException('暂无权限执行此操作');
        }

        if (Yii::$app->request->post('Admin')['submit'] == 'ok') {
            AuthAssignment::deleteAll(['user_id' => $id]);

            $newPrivileges = Yii::$app->request->post('privilege');
            if (!empty($newPrivileges)) {
                foreach ($newPrivileges as $privilege) {
                    $arAssignment = new AuthAssignment();
                    $arAssignment->item_name = $privilege;
                    $arAssignment->user_id = $id;
                    $arAssignment->created_at = time();
                    $arAssignment->save();
                }
            }

            $this->redirect(['index']);
        }

        $model = $this->findModel($id);
        $privileges = AuthItem::find()->select(['name', 'description'])->where(['type' => 1])->all();

        $_privileges = [];
        foreach ($privileges as $privilege){
            $_privileges[$privilege['name']] = Yii::t('app', $privilege['description']);
        }

        $assignments = AuthAssignment::find()->select(['item_name'])->where(['user_id' => $id])->all();

        $assignments = ArrayHelper::getColumn($assignments, 'item_name');


        return $this->render('privilege', [
            'model' => $model,
            'privileges' => $_privileges,
            'assignments' => $assignments,
        ]);
    }

    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
