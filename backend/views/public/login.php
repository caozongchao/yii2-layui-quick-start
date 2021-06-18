<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use backend\assets\AppAsset;

$main_title = '后台';
$this->title = '登入 - '.$main_title;
//$this->registerJs($this->render('js/login.js'));

AppAsset::addCss($this,'@web/statics/layuiadmin/style/login.css');
AppAsset::addScript($this,'@web/statics/js/login.js');
?>

<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2><?php echo $main_title;?></h2>
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class'=>'layui-form'],
            'enableClientValidation' => false,
        ]); ?>

        <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
            <?= $form->field($model, 'username',[
                'template' => '<div class="layui-form-item"><label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>{input}{error}</div>',
            ])->textInput(['class' => 'layui-input','placeholder' => '用户名','id' => 'LAY-user-login-username','lay-verify' => 'required','autocomplete'=>"off"])->label(false) ?>

            <?= $form->field($model, 'password',[
                'template' => '<div class="layui-form-item"><label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>{input}{error}</div>',
            ])->passwordInput(['class' => 'layui-input','placeholder' => '密码','id' => 'LAY-user-login-password','lay-verify' => 'required','autocomplete'=>"off" ])->label(false) ?>

            <div class="layui-form-item" style="margin-bottom: 20px;">
                <input type="checkbox" name="LoginForm[rememberMe]" lay-skin="primary" value="1" title="自动登录">
            </div>

            <div class="layui-form-item">
                <?= Html::submitButton('登 录', ['class' => 'layui-btn layui-btn-fluid', 'lay-submit lay-filter' => 'user-login-submit']) ?>

            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

    <div class="layui-trans layadmin-user-login-footer">
        <p>ADKS © 2020 <a href="https://www.adksedu.com/" target="_blank">adksedu.com</a></p>
    </div>

</div>
<script>
window.onload = function(){
    if(top.location.href !== location.href){
        top.location.href = location.href;
    }
}
</script>
