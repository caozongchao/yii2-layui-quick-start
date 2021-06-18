<?php

namespace common\services;

use Yii;
use common\models\Mall;

class MallService
{
    /**
     * @param $uwid
     * @param $appid
     * @return bool
     * @throws \Exception
     */
    public static function add($uwid,$appid)
    {
        $check1 = Mall::find()->where(['uw_id' => $uwid])->one();
        if($check1){
            throw new \Exception('您已经添加过小程序');
        }
        $check2 = Mall::find()->where(['appid' => $appid])->one();
        if($check2){
            throw new \Exception('小程序已被添加过');
        }
        $model = new Mall();
        $model->uw_id = $uwid;
        $model->appid = $appid;
        if(Yii::$app->globalSetting->get('check_mall') == 'YES'){
            $model->status = 0;
        }else{
            $model->status = 1;
        }
        if($model->save()){
            return true;
        }else{
            throw new \Exception('异常');
        }
    }

    /**
     * @param $uwid
     * @return mixed
     * @throws \Exception
     */
    public static function get($uwid,$flag = true)
    {
        $check = Mall::find()->where(['uw_id' => $uwid,'status' => 1])->one();
        if($check){
            return $check->appid;
        }else{
            if($flag){
                throw new \Exception('提供您的小程序appid后关注官方账户点击官方账户商城跳转官方商城交付押金');
            }else{
                return '';
            }
        }
    }
}