<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>后台登录 - 楼下见|CUHERE</title>
<link rel="stylesheet" href="__PUBLIC__/Css/reset.css">
<link rel="stylesheet" href="__PUBLIC__/Css/login.css">
<script src="__PUBLIC__/Js/jquery.js"></script>
</head>
<body>
<!-- header start -->
<div id="header">
	<img src="__PUBLIC__/Images/logo.png">
</div>
<!-- header end -->

<!-- content start -->
<div id="content">
	<div class="login_area">
		<form action="<?php echo U(GROUP_NAME.'/Login/login');?>" method="post">
		<input class="form_text" type="text" name="username" id="username" placeholder="用户名">
		<input class="form_text" type="password" name="password" id="password" placeholder="密码">
		<input class="form_btn" type="submit" value="">
		</form>
	</div>
	<p class="link_site">Copyright 2014 @CUhere social networking service company.</p>
</div>
<!-- content end -->
</body>
</html>