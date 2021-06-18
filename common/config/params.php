<?php
return [
    'apiHost' => 'https://dytest.api.adksedu.com',
    'mini.appId' => 'wx3d215440ceb32301',
    'mini.appSecret' => 'bf0c356c0d7ba2a8df0aa43a5c45a2da',
    'mini.authUrl' => 'https://api.weixin.qq.com/sns/jscode2session?',
    'mini.tokenUrl' => 'https://api.weixin.qq.com/cgi-bin/token?',

    'jwt.duration' => 2592000,
    'jwt.secret' => 'LittleDY',

    'cover.width' => 345,
    'cover.height' => 170,

    //ffmpeg配置
    'ffmpeg.config' => [
        'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
        'ffprobe.binaries' => '/usr/bin/ffprobe',
        'timeout'          => 3600,
        'ffmpeg.threads'   => 24,
    ],
];