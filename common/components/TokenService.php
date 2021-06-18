<?php

namespace common\components;

use yii;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use common\helpers\CommonHelper;

class TokenService
{
    public static function gen($uwId)
    {
        $signer = new Sha256();
        $duration = Yii::$app->params['jwt.duration'];
        $expire = time() + $duration;
        $tokenBuilder = (new Builder())->issuedBy(Yii::$app->request->getHostInfo())->permittedFor(isset($_SERVER['HTTP_ORIGIN']) ?? '')->issuedAt(time())->canOnlyBeUsedAfter(time())->expiresAt($expire);
        $tokenBuilder->withClaim('id', $uwId);
        //使用Sha256加密生成token对象，该对象的字符串形式为一个JWT字符串
        $jwtSecret = Yii::$app->params['jwt.secret'];
        $token = strval($tokenBuilder->getToken($signer, new Key($jwtSecret)));
        return $token;
    }

    /**
     * @param bool $force
     * @return int|mixed
     * @throws yii\web\UnauthorizedHttpException
     */
    public static function check($force = true)
    {
        $authorization = Yii::$app->request->headers['authorization'];
        $token = trim(str_replace('Bearer', '', $authorization));
        if (empty($token)) {
            if($force){
                throw new yii\web\UnauthorizedHttpException('请登录');
            }else{
                return 0;
            }
        }
        $jwtToken = (new Parser())->parse($token);
        $data = new ValidationData();
        if (!$jwtToken->validate($data)) {
            throw new yii\web\UnauthorizedHttpException('数据校验失败');
        }
        $jwtSecret = Yii::$app->params['jwt.secret'];
        $signer = new Sha256();
        if (!$jwtToken->verify($signer, new Key($jwtSecret))) {
            throw new yii\web\UnauthorizedHttpException('token校验失败');
        }
        $jwtToken->getHeaders();
        $jwtToken->getClaims();
        $id = $jwtToken->getClaim('id');
        return $id;
    }

    /**
     * 小程序access_token生成
     * @return mixed
     * @throws \Exception
     */
    public static function accessToken()
    {
        $check = Yii::$app->cache->get('mini.token');
        if($check){
            return $check;
        }
        $params = [
            'appid' => Yii::$app->params['mini.appId'],
            'secret' => Yii::$app->params['mini.appSecret'],
            'grant_type' => 'client_credential'
        ];
        $url = Yii::$app->params['mini.tokenUrl'].http_build_query($params);
        $response = CommonHelper::curlGet($url);
        Yii::error('======='.$url);
        Yii::error('======='.$response);
        $responseArray = json_decode($response,true);
        if(isset($responseArray['access_token'])){
            Yii::$app->cache->set('mini.token',$responseArray['access_token'],$responseArray['expires_in']);
            return $responseArray['access_token'];
        }
        throw new \Exception('获取令牌失败，请重试');
    }
}