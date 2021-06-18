<?php

use rbac\AnimateAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'items' => $model->getItems(),
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('js/_script.js'));
$animateIcon = ' <i class="layui-icon layui-icon-loading layui-icon layui-anim layui-anim-rotate layui-anim-loop"></i>';

$this->registerJs($this->render('js/index.js'));
$this->registerJs($this->render('js/_script.js'));
?>
<div class="auth-item-view">
    <div class="row" style="margin-right: 2px;margin-left: 15px;">
        <div class="col-sm-11">
            <?=
                DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'layui-table'],
                    'attributes' => [
                        'name',
                        'description:ntext',
                        'ruleName',
                        'data:ntext',
                    ],
                    'template' => '<tr><th style="width:8%">{label}</th><td>{value}</td></tr>',
                ]);
            ?>
        </div>
        <div class="layui-card">
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
                            <?=Html::a('<i class="layui-icon layui-icon-next"></i>' . $animateIcon, ['assign','id' => $model->name], ['class' => 'layui-btn layui-btn-normal btn-assign','data-target' => 'available','title' => Yii::t('rbac-admin', 'Assign'),]);?>
                            <?=Html::a('<i class="layui-icon layui-icon-prev"></i>' . $animateIcon, ['remove','id' => $model->name], ['class' => 'layui-btn layui-btn-danger btn-assign','data-target' => 'assigned','title' => Yii::t('rbac-admin', 'Remove'),]);?>
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
