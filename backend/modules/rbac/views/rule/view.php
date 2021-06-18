<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

?>
<div class="auth-item-view">
    <?php
    echo DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
        'attributes' => [
            'name',
            'className',
        ],
		'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
    ]);
    ?>
</div>
