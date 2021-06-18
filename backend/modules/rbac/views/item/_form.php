<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use rbac\components\RouteRule;
use rbac\AutocompleteAsset;
use yii\helpers\Json;
use rbac\components\Configs;

$context = $this->context;
$labels = $context->labels();
$rules = Configs::authManager()->getRules();
unset($rules[RouteRule::RULE_NAME]);
$source = Json::htmlEncode(array_keys($rules));

$js = <<<JS
    $('#rule_name').autocomplete({
        source: $source,
    });
JS;
AutocompleteAsset::register($this);
$this->registerJs($js);

$this->registerJs($this->render('js/index.js'));
?>

<div class="layui-form" lay-filter="layuiadmin-form-role" id="layuiadmin-form-role" style="padding: 20px 30px 0 0;">
    <?php $form = ActiveForm::begin(['id' => 'item-form']); ?>

    <?= $form->field($model, 'name',[
        'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
        'labelOptions' => ['class' => 'layui-form-label'],
        'errorOptions' => ['class' => 'help-block'],
        'hintOptions' => ['class' => 'hint-block'],
    ])->textInput(['maxlength' => true,'class' => 'layui-input']);?>

    <?= $form->field($model, 'description',[
        'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
        'labelOptions' => ['class' => 'layui-form-label'],
        'errorOptions' => ['class' => 'help-block'],
        'hintOptions' => ['class' => 'hint-block'],
    ])->textarea(['maxlength' => true,'class' => 'layui-textarea']);?>

    <?= $form->field($model, 'ruleName',[
        'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
        'labelOptions' => ['class' => 'layui-form-label'],
        'errorOptions' => ['class' => 'help-block'],
        'hintOptions' => ['class' => 'hint-block'],
    ])->textInput(['maxlength' => true,'class' => 'layui-input']);?>

    <?= $form->field($model, 'data',[
        'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
        'labelOptions' => ['class' => 'layui-form-label'],
        'errorOptions' => ['class' => 'help-block'],
        'hintOptions' => ['class' => 'hint-block'],
    ])->textarea(['maxlength' => true,'class' => 'layui-textarea']);?>

    <div class="layui-form-item" align='right'>
        <?= Html::submitButton('提 交', ['class' => 'layui-btn layui-btn-success','name' => 'submit-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
