<?php

use yii\widgets\DetailView;

?>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">资料</div>
                <div class="layui-card-body" pad15>

                    <div class="layui-form" lay-filter="">
                        <?=DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'layui-table'],
                            'attributes' => [
                                'username',
                                'email:email',
                                'created_at:date',
                                'status',
                            ],
                            'template' => '<tr><th width="90px;">{label}</th><td>{value}</td></tr>',
                        ])
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>




