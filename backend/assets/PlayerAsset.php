<?php

namespace backend\assets;

use yii\web\AssetBundle;

class PlayerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'statics/player/css/aliplayer-min.css',
    ];
    public $js = [
        'statics/player/js/aliplayercomponents-1.0.5.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
