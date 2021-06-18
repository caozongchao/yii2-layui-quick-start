<?php

use yii\helpers\Html;
use backend\assets\AppAsset;
use backend\assets\LockFormAsset;

AppAsset::register($this);
// LockFormAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://assets.adksedu.com/assets/pace/pace.min.js"></script>
    <link href="https://assets.adksedu.com/assets/pace/pace-theme-flash.css" rel="stylesheet">
    <link rel="stylesheet" href="/statics/iconfont/iconfont.css">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php echo $content; ?>

<?php $this->endBody() ?>
<script>
layui.use(['layer'],function () {
    var layer = layui.layer;
    <?php if (\Yii::$app->session->getFlash('error')):?>
    layer.msg("<?php echo \Yii::$app->session->getFlash('error') ?>");
    <?php endif;?>
})
</script>
</body>
</html>
<?php $this->endPage() ?>
