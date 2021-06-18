<?php

use rbac\components\MenuHelper;
use backend\widgets\MenuWidget;

?>
<?php
$callback = function($menu){
    $items = $menu['children'];
    $return = [
        'label' => $menu['name'],
        'url' => [$menu['route']],
    ];
    if(isset($menu['icon'])){
        $return['icon'] = $menu['icon'];
    }else{
        $return['icon'] = 'layui-icon layui-icon-circle';
    }
    $items && $return['items'] = $items;
    return $return;
};
$menu = MenuWidget::widget([
    'options' => ['class' => 'layui-nav layui-nav-tree', 'lay-shrink'=>"all",'id'=>"LAY-system-side-menu",'lay-filter'=>"layadmin-system-side-menu"],
    'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback,true),
    //'submenuTemplate' => "\n<ul class='nav nav-pills nav-stacked' role='menu'>\n{items}\n</ul>\n",
]);
?>
<!-- 侧边菜单 -->
<div class="layui-side layui-side-menu">
    <div class="layui-side-scroll">
        <div class="layui-logo" lay-href="<?php echo \yii\helpers\Url::to(['/public/index']); ?>">
            <span>后台</span>
        </div>
        <?=$menu?>
    </div>
</div>