<div class="layui-fluid" style="background-color: #FFFFFF">
    <blockquote class="layui-elem-quote">
        欢迎管理员回来！当前时间: <span id="nowTime"></span> <span id="weekday"></span>
    </blockquote>
</div>

<script type="text/javascript">
    setDate();
    var nowDate1 = "";
    function setDate()
    {
        var date = new Date();
        var year = date.getFullYear();
        nowDate1 = year + "-" + addZero((date.getMonth() + 1)) + "-" + addZero(date.getDate()) + "  ";
        nowDate1 += addZero(date.getHours()) + ":" + addZero(date.getMinutes()) + ":" + addZero(date.getSeconds());
        document.getElementById("nowTime").innerHTML = nowDate1;
        setTimeout('setDate()', 1000);
    }
    function addZero(time) {
        var i = parseInt(time);
        if (i / 10 < 1) {
            i = "0" + i;
        }
        return i;
    }
    var weekday = "星期" + "日一二三四五六".charAt(new Date().getDay());
    document.getElementById("weekday").innerHTML = weekday;
</script>

<?php
$js = <<<JS
    layui.config({
        base: '/statics/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'sample']);
JS;
$this->registerJs($js);