<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>后台管理</title>
<link rel="stylesheet" href="__PUBLIC__/Css/reset.css">
<link rel="stylesheet" href="__PUBLIC__/Css/admin.css">
<link rel="stylesheet" href="__PUBLIC__/Css/page.css">
<script src="__PUBLIC__/Js/jquery.js"></script>
</head>
<body>
<div id="content">
	<?php if(is_array($list)): foreach($list as $key=>$v): ?><div class="box">
		<?php if($v["toreport"] == 1): ?><a class="toreport">该帖子被举报了</a><?php else: endif; ?>
		<p><img width="212" src="<?php echo ($v["pic"]); ?>"></p>
		<p class="text_landmark"><strong>地标：</strong><?php echo (msubstr($v["landmark"],0,10,'utf-8',false)); ?></p>
		<p class="text_label"><strong>标签：</strong><?php echo (msubstr($v["label"],0,10,'utf-8',false)); ?></p>
		<p class="text_describe"><strong>描述：</strong><?php echo (msubstr($v["describe"],0,10,'utf-8',false)); ?></p>
		<p><!--<a class="detail_btn" href="#">查看</a>--><a class="del_btn" href="<?php echo U(GROUP_NAME.'/Article/delete', array('aid' => $v['id']));?>">删除</a></p>
	</div><?php endforeach; endif; ?>
	<div style="clear:both;"></div>
	<div class="megas512_cuhere">
		<?php echo ($page); ?>
	</div>
</div>
</body>
</html>