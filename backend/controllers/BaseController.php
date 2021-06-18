<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)){
            return false;
        }
        $controller = $action->controller->id;
        $action = $action->id;

        if ($controller=='public'){
            return true;
        }

        if (\Yii::$app->user->can($controller.'/*')){
            return true;
        }
        if (\Yii::$app->user->can($controller.'/'.$action)){
            return true;
        }
        if (\Yii::$app->request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            \Yii::$app->response->data = ['code' => 1, 'msg' => '未授权访问'];
            return false;
        }
        throw new UnauthorizedHttpException('未授权访问');
        return true;
    }

}