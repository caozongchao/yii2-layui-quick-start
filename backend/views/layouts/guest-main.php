<?php

use yii\helpers\Html;
use backend\assets\AppAsset;


AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
<?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
<?php $this->head() ?>

</head>

<body>

<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>


</body>
</html>
<?php $this->endPage(true) ?>