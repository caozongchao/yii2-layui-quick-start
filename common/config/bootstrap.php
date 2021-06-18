<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@manager', dirname(dirname(__DIR__)) . '/manager');
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
//证书图片相关文件
Yii::setAlias('@cert_local_dir',dirname(dirname(__DIR__)).'/manager/web/statics/certificate');