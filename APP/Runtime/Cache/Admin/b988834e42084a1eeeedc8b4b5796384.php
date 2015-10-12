<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>后台管理 - 楼下见|CUHERE</title>
<link rel="stylesheet" href="__PUBLIC__/Css/reset.css">
<link rel="stylesheet" href="__PUBLIC__/Css/admin.css">
<style>
/* common */
html, body { overflow-y: hidden; }
</style>
<script src="__PUBLIC__/Js/jquery.js"></script>
<script>
$(function(){
	resize_window();
	
	$("#left a").each(function(index, element) {
		$(this).click(function(){
			$("#left a").removeClass("current");
			$(this).addClass("current");
		});
	});
});
$(window).resize(function(){
	resize_window();
});
//左右内容尺寸管理
function resize_window(){
	$("#left").height($(window).height()-$("#header").height());
	$("#right").height($(window).height()-$("#header").height()).width($(window).width()-$("#left").width());
}
</script>
</head>
<body>
<!-- header start -->
<div id="header">
	<img class="logo" src="__PUBLIC__/Images/logo.png">
	<div class="admin_user">管理员：<?php echo (session('managerusername')); ?>　　<a href="<?php echo U(GROUP_NAME.'/Index/logout');?>">退出</a></div>
</div>
<!-- header end -->

<!-- left start -->
<div id="left">
	<dl>
		<dt class="user_icon">用户管理</dt>
		<dd>-　<a href="<?php echo U(GROUP_NAME.'/User/index');?>" target="rightIframe" class="current">用户列表</a></dd>
	</dl>
	<dl>
		<dt class="article_icon">帖子管理</dt>
		<dd>-　<a href="<?php echo U(GROUP_NAME.'/Article/index');?>" target="rightIframe">帖子列表</a></dd>
		<dd>-　<a href="<?php echo U(GROUP_NAME.'/Article/reportList');?>" target="rightIframe">被举报帖子列表</a></dd>
	</dl>
</div>
<!-- left end -->

<!-- right start -->
<div id="right">
<iframe name="rightIframe" src="<?php echo U('Admin/User/index');?>"></iframe>
</div>
<!-- right end -->
</body>
</html>