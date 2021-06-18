layui.config({
    base : "/statics/layuiadmin/"
}).use(['form','layer','jquery','element'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;
		element = layui.element;
});
