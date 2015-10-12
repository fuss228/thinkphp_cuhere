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
	<table class="tb_list">
		<tr>
			<th width="60">用户头像</th>
			<th>用户昵称</th>
			<th>注册号码</th>
			<th>是否锁定</th>
			<th>操作</th>
		</tr>
		<?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
				<td><img src="<?php echo ($v["headicon"]); ?>"></td>
				<td><?php echo ($v["username"]); ?></td>
				<td><?php echo ($v["mobile"]); ?></td>
				<td><?php if($v["islock"] == 1): ?><span style="color:#900;">已锁定</span><?php else: endif; ?></td>
				<td><a href="<?php echo U(GROUP_NAME.'/User/detail', array('uid' => $v['id']));?>">详情</a> - <?php if($v["islock"] == 1): ?><a style="color:#900;" href="<?php echo U(GROUP_NAME.'/User/lock', array('uid' => $v['id']));?>">取消锁定</a><?php else: ?><a href="<?php echo U(GROUP_NAME.'/User/lock', array('uid' => $v['id']));?>">锁定</a><?php endif; ?></td>
			</tr><?php endforeach; endif; ?>
	</table>
	<div class="megas512">
		<?php echo ($page); ?>
	</div>
</div>
</body>
</html>