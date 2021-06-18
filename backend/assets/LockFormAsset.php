<?php

namespace backend\assets;

use yii\web\AssetBundle;

class LockFormAsset extends AssetBundle
{
    public $sourcePath = '@webroot/statics/js';

    public $js = [
        'lock.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
