<?php

namespace api\controllers;

use yii;
use yii\filters\Cors;
use yii\web\Controller;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        //取消默认authenticator认证，以确保 cors 被首先处理。
        //然后，我们在实施自己的认证程序之前，强制 cors 允许凭据。
        unset($behaviors['authenticator']);

        //设置跨域
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
            ],
        ];

        return $behaviors;
    }

    public function beforeAction($action)
    {
        if(!Yii::$app->request->isPost){
            return false;
        }
        //return true;
        return parent::beforeAction($action);
    }
}