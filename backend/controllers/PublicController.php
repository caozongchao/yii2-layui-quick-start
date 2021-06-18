<?php
namespace backend\controllers;

use Yii;
use backend\models\LoginForm;

class PublicController extends BaseController
{
    public function actionIframe()
    {
        $this->layout = false;
        return $this->render('iframe');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $this->layout = 'guest-main';
        if (!Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $model = new LoginForm();
        if (Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->login()){
                return $this->goHome();
            }
        }

        return $this->render('login',['model'=>$model]);
    }

    /**
     * 退出
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['public/login']);
    }

    public function actionError()
    {
        $this->layout = 'guest-main';
        $error = Yii::$app->errorHandler->exception;
        $err_msg = array();
        if ($error) {
            $ua = Yii::$app->request->getUserAgent();
            $url = Yii::$app->request->getUrl();
            $err_msg['code'] = $error->statusCode;
            $err_msg['msg'] = $error->getMessage();
            // $err_msg['title'] = $error->getName().'('.$error->statusCode.')';
            //$err_msg['ip'] = UtilService::getIP();
            $err_msg['created_time'] = date("Y-m-d H:i:s");
            $err_msg['ua'] = $ua?$ua:'';
            $err_msg['url'] = $url;
        }
        return $this->render('error', $err_msg);
    }
}