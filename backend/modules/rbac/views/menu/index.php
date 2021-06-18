<?php

use yii\helpers\Html;
use leandrogehlen\treegrid\TreeGrid;
use yii\helpers\Url;

$this->registerJs($this->render('js/index.js'));
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
            <div class="layui-form-item">
                <?= Html::a('添加菜单',['create'], ['class' => 'layui-btn layui-default-add']) ?>
            </div>
        </div>

        <div class="layui-card-body layui-form">
            <?= TreeGrid::widget([
                'dataProvider' => $dataProvider,
                'keyColumnName' => 'id',
                'parentColumnName' => 'parent',
                // 'parentRootValue' => '0',
                'pluginOptions' => [
                    'initialState' => 'collapsed',
                ],
                'options' => ['class' => 'layui-table'],
                'columns' => [
                    'id',
                    'name',
                    [
                        'attribute' => 'menuParent.name',
                        'label' => Yii::t('rbac-admin', 'Parent'),
                    ],
                    'route',
                    'order',
                    [
                        'header' => '操作',
                        'class' => 'yii\grid\ActionColumn',
                        'template' =>'{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key){
                                return Html::a('查看', Url::to(['view','id'=>$model->id]), ['class' => "layui-btn layui-btn-xs layui-default-view"]);
                            },
                            'update' => function ($url, $model, $key) {
                                return Html::a('修改', Url::to(['update','id'=>$model->id]), ['class' => "layui-btn layui-btn-normal layui-btn-xs layui-default-update"]);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('删除', Url::to(['delete','id'=>$model->id]), ['class' => "layui-btn layui-btn-danger layui-btn-xs layui-default-delete"]);
                            }
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>


