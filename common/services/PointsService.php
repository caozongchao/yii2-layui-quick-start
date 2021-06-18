<?php

namespace common\services;

use Yii;
use common\models\PointsDetail;
use common\services\UserWechatService;

class PointsService
{
    /**
     * 增加积分
     * @param $uwid
     * @param $num
     * @param $desc
     * @return bool
     * @throws \Exception
     */
    public static function increase($uwid,$num,$desc)
    {
        $uw = UserWechatService::getOneById($uwid);
        $uw->points += $num;

        $pointsDetail = new PointsDetail();
        $pointsDetail->uw_id = $uwid;
        $pointsDetail->num = $num;
        $pointsDetail->type = 0;
        $pointsDetail->sum = $uw->points + $num;
        $pointsDetail->desc = $desc;

        if($uw->save() && $pointsDetail->save()){
            return true;
        }else{
            throw new \Exception('异常');
        }
    }

    /**
     * 消耗积分
     * @param $uwid
     * @param $num
     * @param $desc
     * @return bool
     * @throws \Exception
     */
    public static function decrease($uwid,$num,$desc)
    {
        $uw = UserWechatService::getOneById($uwid);
        $uw->points -= $num;

        $pointsDetail = new PointsDetail();
        $pointsDetail->uw_id = $uwid;
        $pointsDetail->num = $num;
        $pointsDetail->type = 1;
        $pointsDetail->sum = $uw->points - $num;
        $pointsDetail->desc = $desc;

        if($uw->save() && $pointsDetail->save()){
            return true;
        }else{
            throw new \Exception('异常');
        }
    }
}