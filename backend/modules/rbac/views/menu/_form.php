<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use rbac\models\Menu;
use yii\helpers\Json;
use rbac\AutocompleteAsset;

/* @var $this yii\web\View */
/* @var $model rbac\models\Menu */
/* @var $form yii\widgets\ActiveForm */
AutocompleteAsset::register($this);
$opts = Json::htmlEncode([
        'menus' => Menu::getMenuSource(),
        'routes' => Menu::getSavedRoutes(),
    ]);
$this->registerJs("var _opts = $opts;");
$this->registerJs($this->render('js/_script.js'));
?>

<div class="layui-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model, 'parent', ['id' => 'parent_id']); ?>

    <?= $form->field($model, 'name',[
        'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
        'labelOptions' => ['class' => 'layui-form-label'],
        'errorOptions' => ['class' => 'help-block'],
        'hintOptions' => ['class' => 'hint-block'],
    ])->textInput(['maxlength' => true,'class' => 'layui-input']);?>

    <?= $form->field($model, 'parent_name',[
        'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
        'labelOptions' => ['class' => 'layui-form-label'],
        'errorOptions' => ['class' => 'help-block'],
        'hintOptions' => ['class' => 'hint-block'],
    ])->textInput(['maxlength' => true,'class' => 'layui-input','id' => 'parent_name']);?>

    <?= $form->field($model, 'route',[
        'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
        'labelOptions' => ['class' => 'layui-form-label'],
        'errorOptions' => ['class' => 'help-block'],
        'hintOptions' => ['class' => 'hint-block'],
    ])->textInput(['class' => 'layui-input','id' => 'route']);?>

    <?= $form->field($model, 'order',[
        'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
        'labelOptions' => ['class' => 'layui-form-label'],
        'errorOptions' => ['class' => 'help-block'],
        'hintOptions' => ['class' => 'hint-block'],
    ])->textInput(['class' => 'layui-input']);?>

    <div class="layui-form-item">
        <label class="layui-form-label">图标</label>
        <div class="layui-input-block">
            <div class="layui-inline">
                <input placeholder="请输入或选择图标" id="icon" type="text" name="Menu[icon]" value='<?=$model->icon?>' class="layui-input" style="width: 180px">
            </div>
            <div class="layui-inline">
                <?php echo \yii\helpers\Html::button('打开图标',['class'=>'layui-btn open-icon']);?>
            </div>
        </div>
    </div>
	
    <div class="layui-input-block">
        <?= Html::submitButton('提 交', ['class' => 'layui-btn layui-btn-success','name' => 'submit-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
