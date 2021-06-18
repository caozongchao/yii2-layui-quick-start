<?php

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
]);
?>

<div class="article-search">
    <?= $form->field($model, 'name')->textInput(['class'=>'layui-input search_input','placeholder'=>'请输入']) ?>
    <?= $form->field($model, 'route')->textInput(['class'=>'layui-input search_input','placeholder'=>'请输入']) ?>
    <div class="layui-inline">
        <?= Html::submitButton('<i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>', ['class' => 'layui-btn layuiadmin-btn-useradmin']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
