<?php

namespace api\components;

use yii;
use yii\authclient\AuthAction as yiiAuthAction;
use yii\base\Exception;
use common\components\thirdLogin\MiniWechat;
use yii\base\InvalidConfigException;

class AuthAction extends yiiAuthAction
{
    public function authOAuth2($client, $authUrlParams = [])
    {
        $request = Yii::$app->getRequest();

        if (($error = $request->get('error')) !== null) {
            if ($error === 'access_denied' || $error === 'user_cancelled_login' || $error === 'user_cancelled_authorize'){
                // user denied error
                return $this->authCancel($client);
            }
            // request error
            $errorMessage = $request->get('error_description', $request->get('error_message'));
            if ($errorMessage === null) {
                $errorMessage = http_build_query($request->get());
            }
            throw new Exception('Auth error: ' . $errorMessage);
        }

        if (($code = $request->get('code')) !== null) {
            $token = $client->fetchAccessToken($code);
            if($client instanceof MiniWechat){
                $array = json_decode($token,true);
                if(isset($array['openid'])){
                     return $this->authSuccessMini($array,$client);
                }else{
                    return $this->authCancel($client);
                }
            }else{
                if (!empty($token)) {
                    return $this->authSuccess($client);
                }
                return $this->authCancel($client);
            }
        }

        $url = $client->buildAuthUrl($authUrlParams);
        return ['code' => 200,'url' => $url];
    }

    protected function authSuccessMini($array,$client)
    {
        if (!is_callable($this->successCallback)) {
            throw new InvalidConfigException('"' . get_class($this) . '::$successCallback" 必须是可执行回调');
        }

        $response = call_user_func($this->successCallback, $client,$array);

        return $response;
    }
}