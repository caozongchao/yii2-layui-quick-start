<?php

namespace rbac\controllers;

use Yii;
use rbac\models\BizRule;
use yii\web\Controller;
use rbac\models\searchs\BizRule as BizRuleSearch;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use rbac\components\Helper;
use rbac\components\Configs;

class RuleController extends Controller
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
        $searchModel = new BizRuleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new BizRule(null);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();

            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('create', ['model' => $model,]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();

            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('update', ['model' => $model,]);
    }

    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		if(Configs::authManager()->remove($model->item)){
			Helper::invalidate();
			return $this->asJson(['code' => 200,'msg' => '删除成功']);
		}else{
			$errors = $model->firstErrors;
			return $this->asJson(['code' => 400,'msg' => reset($errors)]);
		}
    }

    protected function findModel($id)
    {
        $item = Configs::authManager()->getRule($id);
        if ($item) {
            return new BizRule($item);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
