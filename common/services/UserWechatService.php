<?php

namespace common\services;

use Yii;
use common\models\UserWechat;

class UserWechatService
{
    public static function getOneById($id)
    {
        $model = UserWechat::findOne($id);
        if($model){
            if($model->status == 0){
                throw new \Exception('用户被禁用');
            }
            return $model;
        }else{
            return false;
        }
    }

    public static function getOneByOpenid($openid)
    {
        $model = UserWechat::findOne(['openid' => $openid]);
        if($model){
            if($model->status == 0){
                throw new \Exception('用户被禁用');
            }
            return $model;
        }else{
            return false;
        }
    }

    /**
     * 新添用户
     * @param $userInfo
     * @return UserWechat
     */
    public static function add($userInfo)
    {
        $model = new UserWechat();
        $model->openid = $userInfo['openId'];
        $model->unionid = $userInfo['unionId'] ?? '111111';
        $model->nickname = $userInfo['nickName'];
        $model->avatar = $userInfo['avatarUrl'];
        $model->sex = $userInfo['gender'];
        // $model->area = $userInfo['province'].','.$userInfo['city'];
        if(!$model->save()){
            $errors = $model->firstErrors;
            throw new \Exception(reset($errors));
        }
        return $model;
    }

    /**
     * 设置资料
     * @param $uwid
     * @param $signature
     * @throws \Exception
     */
    public static function setProfile($uwid,$params)
    {
        $uw = self::getOneById($uwid);
        if($params['signature']){
            $uw->signature = $params['signature'];
        }
        if($params['sex']){
            $uw->sex = $params['sex'];
        }
        if($params['area']){
            $uw->area = $params['area'];
        }
        if(!$uw->save()){
            $errors = $uw->firstErrors;
            throw new \Exception(reset($errors));
        }
    }
}