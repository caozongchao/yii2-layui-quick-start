<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->registerJs($this->render('js/index.js'));

$form = ActiveForm::begin([
    'options' => ['id' => 'form-signup','class' => 'layui-form'],
 ]);
 ?>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">

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

                        <?= $form->field($model, 'password',[
                            'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
                            'labelOptions' => ['class' => 'layui-form-label'],
                            'errorOptions' => ['class' => 'help-block'],
                            'hintOptions' => ['class' => 'hint-block'],
                        ])->passwordInput(['maxlength' => true,'class' => 'layui-input']);?>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <?= Html::submitButton('确认提交', ['class' => 'layui-btn', 'name' => 'signup-button']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
