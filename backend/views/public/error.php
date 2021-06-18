<?php
use yii\helpers\Html;

$this->title = $title;
//$this->context->layout = false;
?>
<div class="layui-fluid">
    <div class="layadmin-tips">
        <i class="layui-icon" face>&#xe664;</i>
        <div class="layui-text">
            <?php
            if($code==404){
            ?>
            <h1>
                <span class="layui-anim layui-anim-loop layui-anim-">4</span>
                <span class="layui-anim layui-anim-loop layui-anim-rotate">0</span>
                <span class="layui-anim layui-anim-loop layui-anim-">4</span>
            </h1>
            <?php }?>

            <?= $code .' - '. nl2br(Html::encode($msg)) ?>
        </div>
    </div>
</div>
