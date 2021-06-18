<?php

namespace api\modules\v1\controllers;

use Yii;
use api\controllers\ApiController;
use common\services\VideosService;
use common\components\TokenService;

class IndexController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/v1/index/recommend",
     *     tags={"推荐"},
     *     summary="推荐列表",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="first",type="integer"),
     *             )
     *         )
     *      ),
     *     @OA\Response(response="200",description="ok")
     * )
     */
    public function actionRecommend()
    {
        try {
            $uwid = TokenService::check(false);
            $first = Yii::$app->request->post('first',0);
            $list = VideosService::getRecommend($uwid,$first);
            return $this->asJson(['code' => 1,'msg' => '获取成功','data' => $list]);
        }catch(\Exception $e){
            Yii::error('======='.$e->getTraceAsString());
            return $this->asJson(['code' => 0,'msg' => $e->getMessage()]);
        }
    }
}