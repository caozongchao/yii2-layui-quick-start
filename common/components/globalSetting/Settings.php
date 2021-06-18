<?php

namespace common\components\globalSetting;

use Yii;
use common\components\globalSetting\models\Setting;

class Settings extends \yii\base\Component
{
    public function get($code)
    {
        if(!$code) return ;

        $setting = Setting::find()->where(['code' => $code])->one();

        if($setting)
            return $setting->value;
        else
            return ;
    }

}
