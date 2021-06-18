<?php

namespace rbac\controllers;

use Yii;
use rbac\models\Menu;
use rbac\models\searchs\Menu as MenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use rbac\components\Helper;

class MenuController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new MenuSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Menu;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->menuParent) {
            $model->parent_name = $model->menuParent->name;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		if($model->delete()){
			Helper::invalidate();
			return $this->asJson(['code' => 200,'msg' => '删除成功']);
		}else{
			$errors = $model->firstErrors;
			return $this->asJson(['code' => 400,'msg' => reset($errors)]);
		}
    }

    public function actionDeleteAll(){
        $data = Yii::$app->request->post();
        if($data){
            $model = new Menu;
            $count = $model->deleteAll(['in','id',$data['keys']]);
            if($count>0){
				Helper::invalidate();
                return $this->asJson(['code' => 200,'msg' => '删除成功']);
            }else{
				$errors = $model->firstErrors;
                return $this->asJson(['code' => 400,'msg' => reset($errors)]);
            }
        }else{
            return $this->asJson(['code' => 400,'msg' => '请选择数据']);
        }
    }

    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
