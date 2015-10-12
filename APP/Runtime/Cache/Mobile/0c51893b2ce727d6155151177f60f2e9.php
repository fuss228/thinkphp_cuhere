<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8" />
<!--移动端版本兼容 -->
<script type="text/javascript">
    var phoneWidth =  parseInt(window.screen.width);
    var phoneScale = phoneWidth/640;
    var ua = navigator.userAgent;
    if (/Android (\d+\.\d+)/.test(ua)){
        var version = parseFloat(RegExp.$1);
        if(version>2.3){
            document.write('<meta name="viewport" content="width=640, minimum-scale = '+phoneScale+', maximum-scale = '+phoneScale+', target-densitydpi=device-dpi">');
        }else{
            document.write('<meta name="viewport" content="width=640, target-densitydpi=device-dpi">');
        }
    } else {
        document.write('<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">');
    }
</script>
<!--移动端版本兼容 end -->
<meta http-equiv="X-UA-Compatible" content="IE=edge" >
<!--[if IE 6]><script>alert("您的ie浏览器版本较低，影响页面效果。\n请更新至ie7以上！");</script><![endif]-->
<title>CUhere 楼下见</title>
<meta name="keywords" content="CUhere 楼下见" />
<meta name="description" content="CUhere 楼下见，欢迎来到CUhere，请至APP下载，谢谢" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/reset.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/style.css" />
</head>

<body>
<div id="wrapper">
	<div id="header" class="line-layout">
		<img class="logo" src="__PUBLIC__/Images/logo.jpg">
	</div>
	<div id="content" class="line-layout">
		<img class="conimg" src="__PUBLIC__/Images/kv.jpg">
	</div>
	<div id="footer" class="line-layout">
		<p>楼下見问题支援：<a class="linkmail" href="mailto:kevin@ainjet.com?subject=楼下見问题支援">支援邮箱（kevin@ainjet.com）</a></p>
		<p>Copyright 2014 @CUhere social networking service company.</p>
	</div>
	<div class="clr"></div>
</div>
</body>
</html>