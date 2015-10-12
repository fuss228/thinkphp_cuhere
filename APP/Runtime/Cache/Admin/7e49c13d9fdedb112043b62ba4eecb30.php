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
			<td colspan="2" align="right"><a href="javascript:history.go(-1);">返回</a></td>
		</tr>
		<tr>
			<td width="200">用户头像</td>
			<td><img src="<?php echo ($info["headicon"]); ?>"></td>
		</tr>
		<tr>
			<td>用户昵称</td>
			<td><?php echo ($info["username"]); ?></td>
		</tr>
		<tr>
			<td>注册手机</td>
			<td><?php echo ($info["mobile"]); ?></td>
		</tr>
		<tr>
			<td>个人简介</td>
			<td><?php echo ($info["brief"]); ?></td>
		</tr>
		<tr>
			<td>真实姓名</td>
			<td><?php echo ($info["userinfo"]["realname"]); ?></td>
		</tr>
		<tr>
			<td>性别</td>
			<td><?php if($info.userinfo.sex == 1): ?>女<?php else: ?>男<?php endif; ?></td>
		</tr>
		<tr>
			<td>个人网站</td>
			<td><?php echo ($info["userinfo"]["website"]); ?></td>
		</tr>
		<tr>
			<td>星座</td>
			<td><?php echo ($info["userinfo"]["constellation"]); ?></td>
		</tr>
		<tr>
			<td>家乡-省</td>
			<td><?php echo ($info["userinfo"]["province"]); ?></td>
		</tr>
		<tr>
			<td>家乡-市</td>
			<td><?php echo ($info["userinfo"]["city"]); ?></td>
		</tr>
		<tr>
			<td>家乡-区</td>
			<td><?php echo ($info["userinfo"]["area"]); ?></td>
		</tr>
		<tr>
			<td>常出没城市</td>
			<td><?php echo ($info["userinfo"]["citys"]); ?></td>
		</tr>
		<tr>
			<td>职业</td>
			<td><?php echo ($info["userinfo"]["professional"]); ?></td>
		</tr>
		<tr>
			<td>公司</td>
			<td><?php echo ($info["userinfo"]["company"]); ?></td>
		</tr>
		<tr>
			<td>商圈</td>
			<td><?php echo ($info["userinfo"]["businessCircle"]); ?></td>
		</tr>
		<tr>
			<td>写字楼</td>
			<td><?php echo ($info["userinfo"]["officeBuildings"]); ?></td>
		</tr>
		<tr>
			<td>职位</td>
			<td><?php echo ($info["userinfo"]["position"]); ?></td>
		</tr>
	</table>
</div>
</body>
</html>