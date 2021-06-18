<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            //文档预览地址,配置好后可以直接访问:http://api.yourhost.com/site/doc
            'doc' => [
                'class' => 'light\swagger\SwaggerAction',
                'restUrl' => \yii\helpers\Url::to(['/site/api'], true),
            ],
            'api' => [
                'class' => 'light\swagger\SwaggerApiAction',
                //这里配置需要扫描的目录,不支持yii的alias,所以需要这里直接获取到真实地址
                'scanDir' => [
                    Yii::getAlias('@api/modules/v1/controllers'),
                ],
                //这个下面讲
                'api_key' => 'LittleDY',
            ],
            'error' => [
                'class' => 'api\controllers\ErrorApiAction',
            ],
        ];
    }
}
