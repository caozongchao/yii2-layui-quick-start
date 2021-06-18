<?php

use common\widgets\JsBlock;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline layui-form'],
        'fieldConfig' => [
            'template' => '
    <div class="layui-inline">
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

<div class="article-search">
    <?= $form->field($model, 'username')->textInput(['class'=>'layui-input search_input','placeholder'=>'请输入']) ?>
    <?= $form->field($model, 'email')->textInput(['class'=>'layui-input search_input','placeholder'=>'请输入']) ?>
    <?= $form->field($model, 'begin_end',[
        'template' => "<div class='layui-inline'>{label}<div class='layui-input-inline'>{input}{hint}{error}</div></div>",
        'labelOptions' => ['class' => 'layui-form-label'],
    ])->textInput(['maxlength' => true,'class' => 'layui-input','placeholder' => '添加时间','id' => 'begin_end','autocomplete' => 'off'])->label('添加时间');?>
    <div class="layui-inline">
        <?= Html::submitButton('<i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>', ['class' => 'layui-btn layuiadmin-btn-useradmin']) ?>
    </div>
    <div class="form-group" style="padding-top: 10px;">
        <?= Html::button('添加用户', ['class' => 'layui-btn layui-default-add']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?php JsBlock::begin() ?>
<script>
layui.use(['laydate'], function(){
    layui.laydate.render({
        elem: '#begin_end',
        type: 'date',
        format: 'yyyy/MM/dd',
        range: true,
        trigger : 'click',
    });
});
</script>
<?php JsBlock::end() ?>



