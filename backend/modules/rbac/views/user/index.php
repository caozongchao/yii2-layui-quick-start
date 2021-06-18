<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use rbac\models\User;

$this->registerJs($this->render('js/index.js'));
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>

        <div class="layui-card-body">
            <?=GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
                'tableOptions'=> ['class'=>'layui-table'],
                'pager' => [
                    'options'=>['class'=>'layuipage pull-right'],
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'firstPageLabel'=>'首页',
                    'lastPageLabel'=>'尾页',
                    'maxButtonCount'=>5,
                ],
                'columns' => [
                    'id',
                    [
                        'attribute' => 'username',
                        'contentOptions' => ['style'=> 'text-align: center;'],
                        'headerOptions' => [
                            'width' => '10%',
                            'style'=> 'text-align: center;'
                        ],
                    ],
                    [
                        'attribute' => 'email',
                        'format' => 'email',
                        'contentOptions' => ['style'=> 'text-align: center;'],
                        'headerOptions' => [
                            'width' => '20%',
                            'style'=> 'text-align: center;'
                        ],
                    ],
                    [
                        'attribute' => 'created_at',
                        'contentOptions' => ['class'=>'text-center'],
                        'value' => function($model){
                            return date("Y-m-d H:i:s",$model->created_at);
                        },
                        'headerOptions' => [
                            'style'=> 'text-align: center;'
                        ],
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' => function($model) {
                            return $model->status != User::STATUS_ACTIVE ?'<font color="red">禁用</font>':'<font color="green">启用</font>';
                        },
                        'contentOptions' => ['style'=> 'text-align: center;'],
                        'headerOptions' => [
                            'width' => '10%',
                            'style'=> 'text-align: center;'
                        ]
                    ],
                    [
                        'header' => '操作',
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' => ['class'=>'text-center'],
                        'headerOptions' => [
                            'width' => '20%',
                            'style'=> 'text-align: center;'
                        ],
                        'template' =>'{view} {update} {activate} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key){
                                return Html::a('查看', $url, ['class' => "layui-btn layui-btn-xs layui-default-view"]);
                            },
                            'update' => function ($url, $model, $key) {
                                return Html::a('修改', Url::to(['update','id'=>$model->id]), ['class' => "layui-btn layui-btn-normal layui-btn-xs layui-default-update"]);
                            },
                            'activate' => function ($url, $model, $key) {
                                if($model->status != User::STATUS_ACTIVE){
                                    return Html::a('启用', Url::to(['active','id'=>$model->id]), ['class' => "layui-btn layui-btn-xs layui-btn-normal layui-default-active"]);
                                }else{
                                    return Html::a('禁用', Url::to(['inactive','id'=>$model->id]), ['class' => "layui-btn layui-btn-xs layui-btn-warm layui-default-inactive"]);
                                }
                            },

                            'delete' => function ($url, $model, $key) {
                                return Html::a('删除', $url, ['class' => "layui-btn layui-btn-danger layui-btn-xs layui-default-delete"]);
                            }
                        ]
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
