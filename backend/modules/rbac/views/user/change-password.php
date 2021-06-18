<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('rbac-admin', 'Change Password');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([
    'options' => ['id' => 'form-change','class' => 'layui-form'],
    'fieldConfig' => [
        'template' => '
                        <div class="layui-form-item">
                            <label class="layui-form-label">{label}</label>
                            <div class="layui-input-inline">
                                {input}
                            </div>
                            <div class="layui-form-mid layui-word-aux">{hint}</div>
                        </div>
                        ',
        'options' => [
            'tag' => false,
        ],
    ],

]); ?>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header"><?=$this->title?></div>
                <div class="layui-card-body" pad15>

                    <div class="layui-form" lay-filter="">

<?= $form->field($model, 'oldPassword')->passwordInput(['class'=>'layui-input']) ?>

<?= $form->field($model, 'newPassword')->passwordInput(['class'=>'layui-input']) ?>

<?= $form->field($model, 'retypePassword')->passwordInput(['class'=>'layui-input']) ?>

                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <?= Html::submitButton('确认修改', ['lay-submit lay-filter'=> 'setmyinfo','class' => 'layui-btn layui-btn-normal', 'name' => 'change-button']) ?>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

