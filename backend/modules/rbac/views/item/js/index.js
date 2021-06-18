layui.config({
    base : "/statics/layuiadmin/modules/"
}).use(['form','layer','jquery'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;
    
	//添加
	$(window).one("resize",function(){
		$(".layui-default-add").click(function(){
			var index = layui.layer.open({
				title : "添加",
				type : 2,
                area: ['100%', '100%'],
				content : "<?= yii\helpers\Url::to(['create']); ?>",
                end: function () {
                    location.reload();
                }
			});	
			// layui.layer.full(index);
		});
	}).resize();

	//全选
	form.on('checkbox(allChoose)', function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		child.each(function(index, item){
			item.checked = data.elem.checked;
		});
		form.render('checkbox');
	});

	//通过判断文章是否全部选中来确定全选按钮是否选中
	form.on("checkbox(choose)",function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		var childChecked = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"]):checked')
		if(childChecked.length === child.length){
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = true;
		}else{
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = false;
		}
		form.render('checkbox');
	});
 
	//操作
	$("body").on("click",".layui-default-view",function(){  //查看
        var href = $(this).attr("href");
        console.log(href);
        var index = layui.layer.open({
            title : "查看",
            type : 2,
            area: ['100%', '100%'],
            content : href,
        });	
        layui.layer.full(index);//全屏当前弹出层
        return false;
	});
    
	$("body").on("click",".layui-default-update",function(){  //修改
        var href = $(this).attr("href");
        console.log(href);
        var index = layui.layer.open({
            title : "修改",
            type : 2,
            area: ['100%', '100%'],
            content : [href,"yes"],
        });	
        return false;
	});
    
	$("body").on("click",".layui-default-audit",function(){  //删除
        var href = $(this).attr("href");
		layer.confirm('确定审核此文章吗？',{icon:3, title:'提示信息'},function(index){
            $.post(href,function(data){
                if(data.code===200){
                    layer.msg(data.msg);
                    layer.close(index);
                    setTimeout(function(){
                       location.reload();
                    },500);
                }else{
                    layer.close(index);
                    layer.msg(data.msg);
                }
            },"json").fail(function(a,b,c){
                if(a.status==403){
                    layer.msg('没有权限');
                }else{
                    layer.msg('系统错误');
                }
            });
		});
        return false;
	});

	$("body").on("click",".layui-default-delete",function(){  //删除
        var href = $(this).attr("href");
		layer.confirm('确定删除此条记录吗？',{icon:3, title:'提示信息'},function(index){
            $.post(href,function(data){
                if(data.code===200){
                    layer.msg(data.msg);
                    layer.close(index);
                    setTimeout(function(){
                       location.reload();
                    },500);
                }else{
                    layer.close(index);
                    layer.msg(data.msg);
                }
            },"json").fail(function(a,b,c){
                if(a.status==403){
                    layer.msg('没有权限');
                }else{
                    layer.msg('系统错误');
                }
            });
		});
        return false;
	});
});
