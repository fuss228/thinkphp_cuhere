<?php
//注册模块
Class RegisterAction extends Action{
	
	//获取手机验证码
	public function getPhoneCode(){		
		
		$mobile=I('mobile');//手机号码
				
		//验证手机号码格式
		if(!isMobile($mobile)){
			 $this->ajaxReturn(0, "手机号码格式错误", -1);
		}
		//判断手机号码是否重复
		if(M('user')->where(array('mobile' => $mobile))->count('id')>0){
			$this->ajaxReturn(0, "该手机号码已被注册", -2);
		}
		//发送短信验证码
		$mobileCode=createMoibleCode();
		
		if(sendMobileCode($mobile, '验证码：'.$mobileCode)){
			session('regMobile', $mobile);//注册手机号码传入session
			session('mobileCode', $mobileCode);//短信验证码传入session
			session('registerTime', time());//注册时间传入session
			$this->ajaxReturn(0, "操作成功", 1);
		}else{
			$this->ajaxReturn(0, "系统错误", -101);
		}
	}
	
	//手机验证码确认
	public function verifyPhoneCode(){
	
		$phoneCode=I('phoneCode');//手机验证码
				
		//验证超时
		if(session('registerTime')+180<time()){
			$this->ajaxReturn(0, "180秒验证超时", -1);
		}
		//确认手机验证码
		if($phoneCode!=session('mobileCode')){
			$this->ajaxReturn(0, "验证码错误", -2);
		}
		
		$this->ajaxReturn(0, "验证成功", 1);
	}
	
	//新用户注册
	public function newUser(){
		
		$username=I('username');//昵称
		$password=I('password');//密码
		$confirmPassword=I('confirmPassword');//确认密码
		
		//判断用户昵称格式
		if(!verifyUsername($username)){
			$this->ajaxReturn(0, "用户昵称格式错误（4-30个字符，中英文、数字、下划线）", -1);
		}
		//判断密码长度
		if(strlen($password)<6 || strlen($password)>20){
			$this->ajaxReturn(0, "密码必须保持在6到20个字符之间", -2);
		}
		//判断确认密码
		if($password!=$confirmPassword){
			$this->ajaxReturn(0, "两次密码输入不一致", -3);
		}
		//判断用户昵称是否重复
		if(M('user')->where(array('username' => $username))->count('id')>0){
			$this->ajaxReturn(0, "该用户昵称已存在", -4);
		}
		//判断手机号码是否重复
		if(M('user')->where(array('mobile' => session('regMobile')))->count('id')>0){
			$this->ajaxReturn(0, "该手机号码已被注册", -5);
		}
		
		$data=array(
			'mobile' => session('regMobile'),
			'username' => $username,
			'password' => md5($password),
		
		);
		if($id=M('user')->add($data)){
			M('userinfo')->add(array(
				'uid' => $id,
				'time' => time() 
			));
			session('uid', $id);
			$this->ajaxReturn(array('uid'=>$id), "注册成功", 1);
		}else{
			$this->ajaxReturn(0, "系统错误", -101);
		}
	}


}
?>