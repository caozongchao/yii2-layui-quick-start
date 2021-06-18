<?php
/**
 * crontab定时脚本添加以下代码即可
 # * * * * * docker exec -i php /bin/bash -c "/usr/local/bin/php /data/www/sas/yii schedule/run --scheduleFile=@console/config/schedule.php" 1>/tmp/1.log 2>&1
 */
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

// $schedule->exec('date')->everyMinute();
/*$schedule->call(function(){
    echo time();
})->everyMinute();*/
// $schedule->command('foo')->everyFiveMinutes();
