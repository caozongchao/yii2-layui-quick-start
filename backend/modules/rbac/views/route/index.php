<?php

use yii\helpers\Html;
use yii\helpers\Json;
//use yii\web\YiiAsset;
//use rbac\AnimateAsset;

//AnimateAsset::register($this);
//YiiAsset::register($this);

$opts = Json::htmlEncode([
    'routes' => $routes,
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('js/_script.js'));
$animateIcon = ' <i class="layui-icon layui-icon-loading layui-icon layui-anim layui-anim-rotate layui-anim-loop"></i>';
?>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">路由管理</div>
                <div class="layui-card-body" pad15>

                    <div class="layui-row layui-col-space10">

                        <div class="layui-transfer  layui-border-box" lay-filter="LAY-transfer-1">
                            <div class="layui-transfer-box" style="width: 45%; height: 400px;">

                                <div class="layui-transfer-search"><i class="layui-icon layui-icon-search"></i>
                                    <input class="layui-input search" data-target="available" placeholder="<?=Yii::t('rbac-admin', 'Search for available');?>">
                                </div>

                                <div class="layui-transfer-data" style="height: 385px;">
                                    <select multiple style="width: 100%; height: 360px;" class="layui-transfer-data list" size="35" data-target="available"></select>

                                </div>
                            </div>
                            <div class="layui-transfer-active">
                                <?=Html::a('<i class="layui-icon layui-icon-next"></i>' . $animateIcon, ['assign'], ['class' => 'layui-btn layui-btn-normal btn-assign','data-target' => 'available','title' => Yii::t('rbac-admin', 'Assign'),]);?>
                                <?=Html::a('<i class="layui-icon layui-icon-prev"></i>' . $animateIcon, ['remove'], ['class' => 'layui-btn layui-btn-danger btn-assign','data-target' => 'assigned','title' => Yii::t('rbac-admin', 'Remove'),]);?>

                                <?= Html::a('<i class="layui-icon layui-icon-refresh"></i>'.$animateIcon,['refresh'], ['class' => 'layui-btn layui-btn-refresh','id' => 'btn-refresh','title' => Yii::t('rbac-admin', 'Refresh'),'style' => 'margin-top:15px;']) ?>
                            </div>
                            <div class="layui-transfer-box" style="width: 45%; height: 400px;">

                                <div class="layui-transfer-search"><i class="layui-icon layui-icon-search"></i>
                                    <input class="layui-input search" data-target="assigned" placeholder="<?=Yii::t('rbac-admin', 'Search for assigned');?>">

                                </div>
                                <div class="layui-transfer-data" style="height: 385px;">
                                    <select multiple style="width: 100%; height: 360px;" class="layui-transfer-data list" size="35" data-target="assigned"></select>

                                </div>
                            </div>

                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block"><!-- 站位 --></div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

