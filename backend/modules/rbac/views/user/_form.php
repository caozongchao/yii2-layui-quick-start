<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJs($this->render('js/index.js'));
?>

<?php $form = ActiveForm::begin([
    'options' => ['class' => 'layui-form'],
]); ?>
<div class="layui-card">
    <div class="layui-card-header">设置资料</div>
    <div class="layui-card-body" pad15>
        <div class="layui-form" lay-filter="">
            <?= $form->field($model, 'username',[
                'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
                'labelOptions' => ['class' => 'layui-form-label'],
                'errorOptions' => ['class' => 'help-block'],
                'hintOptions' => ['class' => 'hint-block'],
            ])->textInput(['maxlength' => true,'class' => 'layui-input']);?>

            <?= $form->field($model, 'email',[
                'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
                'labelOptions' => ['class' => 'layui-form-label'],
                'errorOptions' => ['class' => 'help-block'],
                'hintOptions' => ['class' => 'hint-block'],
            ])->textInput(['maxlength' => true,'class' => 'layui-input']);?>

            <?= $form->field($model, 'password_hash',[
                'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
                'labelOptions' => ['class' => 'layui-form-label'],
                'errorOptions' => ['class' => 'help-block'],
                'hintOptions' => ['class' => 'hint-block'],
            ])->passwordInput(['maxlength' => true,'class' => 'layui-input','value' => '']);?>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <?= Html::submitButton($model->isNewRecord ? '确认添加' : '确认修改', ['lay-submit lay-filter'=> 'setmyinfo','class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

