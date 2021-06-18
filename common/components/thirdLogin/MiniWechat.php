<?php

namespace common\components\thirdLogin;

class MiniWechat
{
    private $appid;
    private $sessionKey;

    /**
     * 构造函数
     * @param $sessionKey string 用户在小程序登录后获取的会话密钥
     * @param $appid string 小程序的appid
     */
    public function __construct($appid,$sessionKey)
    {
        $this->sessionKey = $sessionKey;
        $this->appid = $appid;
    }

    /**
     * @param $encryptedData
     * @param $iv
     * @param $data
     * @throws \Exception
     */
    public function decryptData($encryptedData,$iv,&$data)
    {
        if (strlen($this->sessionKey) != 24) {
            throw new \Exception(ErrorCode::$IllegalAesKey);
        }
        $aesKey = base64_decode($this->sessionKey);

        if (strlen($iv) != 24) {
            throw new \Exception(ErrorCode::$IllegalIv);
        }

        $aesIV = base64_decode($iv);

        $aesCipher = base64_decode($encryptedData);

        $result = openssl_decrypt($aesCipher,"AES-128-CBC",$aesKey,1,$aesIV);

        $dataObj = json_decode($result);

        if($dataObj == NULL){
            throw new \Exception(ErrorCode::$IllegalBuffer);
        }

        if( $dataObj->watermark->appid != $this->appid){
            throw new \Exception(ErrorCode::$IllegalBuffer);
        }
        $data = $result;
    }
}