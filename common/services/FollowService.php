<?php

namespace common\services;

use Yii;
use common\models\UserWechat;
use common\models\UwFans;
use common\services\UserWechatService;

class FollowService
{
    /**
     * 关注
     * @param $uwid
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function follow($uwid,$id)
    {
        $check = UwFans::find()->where(['uw_id' => $id,'fan_id' => $uwid])->one();
        if($check){
            throw new \Exception('已关注此用户');
        }
        $model = new UwFans();
        $model->uw_id = $id;
        $model->fan_id = $uwid;
        $uw = UserWechatService::getOneById($id);
        $uw->fans_counts += 1;
        $uw1 = UserWechatService::getOneById($uwid);
        $uw1->follow_counts += 1;
        if($model->save() && $uw->save() && $uw1->save()){
            return true;
        }else{
            throw new \Exception('异常');
        }
    }

    /**
     * 获取粉丝列表
     * @param $params
     * @return array
     */
    public static function getFansList($params)
    {
        $query = UwFans::find()->from(UwFans::tableName().' uwf')->where(['uwf.uw_id' => $params['uwid']])->leftJoin(UserWechat::tableName().' uw','uw.id=uwf.fan_id')->andWhere(['uw.status' => 1]);
        $start = ($params['page'] -1) * $params['per'];
        $q = clone $query;
        $count = $q->count();
        $fasIds = $query->orderBy(['uwf.id' => SORT_DESC])->limit($params['per'])->offset($start)->select(['uwf.fan_id'])->column();
        $data = UserWechat::find()->where(['in','id',$fasIds])->orderBy(['id' => SORT_DESC])->asArray()->all();
        if($data){
            foreach ($data as $key => &$value) {
                $value['avatar'] = strstr($value['avatar'],'http') ? $value['avatar'] : Yii::$app->params['apiHost'].$value['avatar'];
                //检测关注
                $checkFollow = UwFans::find()->where(['uw_id' => $value['id'],'fan_id' => $params['uwid']])->exists();
                if($checkFollow){
                    $value['is_follow_back'] = 1;
                }else{
                    $value['is_follow_back'] = 0;
                }
            }
        }
        return [
            'count' => $count,
            'data' => $data,
            'pageCount' => ceil($count/$params['per'])
        ];
    }

    /**
     * 取消关注
     * @param $uwid
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function unfollow($uwid,$id)
    {
        $model = UwFans::find()->where(['uw_id' => $id,'fan_id' => $uwid])->one();
        if(!$model){
            throw new \Exception('未关注此用户');
        }
        $uw = UserWechatService::getOneById($id);
        $uw->fans_counts -= 1;
        if($uw->fans_counts < 0){
            $uw->fans_counts = 0;
        }
        $uw1 = UserWechatService::getOneById($uwid);
        $uw1->follow_counts -= 1;
        if($uw1->follow_counts < 0){
            $uw1->follow_counts = 0;
        }
        if($model->delete() && $uw->save() && $uw1->save()){
            return true;
        }else{
            throw new \Exception('异常');
        }
    }

    /**
     * 获取关注列表
     * @param $params
     * @return array
     */
    public static function getFollowsList($params)
    {
        $query = UwFans::find()->from(UwFans::tableName().' uwf')->where(['uwf.fan_id' => $params['uwid']])->leftJoin(UserWechat::tableName().' uw','uw.id=uwf.uw_id')->andWhere(['uw.status' => 1]);
        if($params['name']){
            $query->andWhere(['like','uw.nickname',$params['name']]);
        }
        $start = ($params['page'] -1) * $params['per'];
        $q = clone $query;
        $count = $q->count();
        $followIds = $query->orderBy(['uwf.id' => SORT_DESC])->limit($params['per'])->offset($start)->select(['uwf.uw_id'])->column();

        $data = UserWechat::find()->where(['in','id',$followIds])->orderBy(['id' => SORT_DESC])->asArray()->all();
        if($data){
            foreach ($data as $key => &$value) {
                $value['avatar'] = strstr($value['avatar'],'http') ? $value['avatar'] : Yii::$app->params['apiHost'].$value['avatar'];
            }
        }
        return [
            'count' => $count,
            'data' => $data,
            'pageCount' => ceil($count/$params['per'])
        ];
    }
}