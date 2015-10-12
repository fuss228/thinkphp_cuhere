<?php
//后台登录控制器
Class LoginAction extends Action{
	
	//登录视图
	public function index(){
		$this->display();
	}
	
	//登录操作
	public function login(){
		if(!IS_POST) _404('页面不存在！');
		
		$username=I('username');
		$password=I('password', '', 'md5');
		$manager=M('manager')->where(array('username' => $username))->find();
		if(!$manager || $manager['password']!=$password){
			$this->error('账号或密码错误！');
		}
		
		session('managerid', $manager['id']);
		session('managerusername', $manager['username']);
				
		redirect(__GROUP__);
	}
}
?>