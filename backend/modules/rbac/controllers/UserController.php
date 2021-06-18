<?php

namespace rbac\controllers;

use Yii;
use rbac\models\form\Login;
use rbac\models\form\PasswordResetRequest;
use rbac\models\form\ResetPassword;
use rbac\models\form\Signup;
use rbac\models\form\ChangePassword;
use rbac\models\User;
use rbac\models\searchs\User as UserSearch;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\base\UserException;
use yii\mail\BaseMailer;
use yii\web\ForbiddenHttpException;

/**
 * User controller
 */
class UserController extends Controller
{
    private $_oldMailPath;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'active' => ['post'],
					'inactive' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
			'model' => $this->findModel($id),
        ]);
    }

    public function actionDelete($id)
    {
		if($this->findModel($id)->delete()){
			return $this->asJson(['code' => 200,'msg' => '删除成功']);
		}else{
			return $this->asJson(['code' => 400,'msg' => '删除失败']);
		}
    }

    public function actionActive($id)
    {
		$model = $this->findModel($id);
		if($model->status == User::STATUS_ACTIVE){
			return $this->asJson(['code' => 400,'msg' => '该用户是已经是启用状态']);
		}

		$model->status = User::STATUS_ACTIVE;

		if($model->save()){
			return $this->asJson(['code' => 200,'msg' => '启用成功']);
		}else{
			$errors = $model->firstErrors;
			return $this->asJson(['code' => 400,'msg' => reset($errors)]);
		}
    }

    public function actionInactive($id)
    {
		$model = $this->findModel($id);
		if($model->status == User::STATUS_INACTIVE){
			return $this->asJson(['code' => 400,'msg' => '该用户是已经是禁用状态']);
		}

		$model->status = User::STATUS_INACTIVE;

		if($model->save()){
			return $this->asJson(['code' => 200,'msg' => '禁用成功']);
		}else{
			$errors = $model->firstErrors;
			return $this->asJson(['code' => 400,'msg' => reset($errors)]);
		}
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post_data = Yii::$app->request->post();
        $currentRole = Yii::$app->authManager->getRolesByUser($id);
        $currentRoleName = array_keys($currentRole);
        $currentRoleName = implode(',', $currentRoleName);
        if ($model->load($post_data) && $model->validate()) {
			if($post_data['User']['password_hash']){
				$model->password_hash = Yii::$app->security->generatePasswordHash($post_data['User']['password_hash']);
			}
            $model->password_reset_token = null;
            $model->updated_at = time();
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'currentRoleName' => $currentRoleName,
        ]);
    }

    public function actionSignup()
    {
        $model = new Signup();
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($user = $model->signup()) {
				return $this->render('view', [
					'model' => $user,
				]);
            }
        }
        return $this->render('signup', [
                'model' => $model,
        ]);
    }

    public function actionChangePassword()
    {
        $model = new ChangePassword();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->change()) {
            return $this->goHome();
        }
        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}