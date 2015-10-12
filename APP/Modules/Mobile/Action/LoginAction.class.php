<?php
//登录模块
Class LoginAction extends Action{
	
	//登录操作
	public function doLogin(){	
		import('Class.ServerAPI', APP_PATH);
		

		$mobile=I('mobile');//手机号码
		$password=I('password', '', 'md5');//密码
		
		//验证手机号码格式
		if(!isMobile($mobile)){
			 $this->ajaxReturn(0, "手机号码格式错误", -1);
		}
		
		//判断该手机号码是否存在
		if(M('user')->where(array('mobile' => $mobile))->count('id')==0){
			$this->ajaxReturn(0, "手机号码不存在", -2);
		}

		//判断密码是否正确
		if(M('user')->where(array('mobile' => $mobile, 'password' => $password))->count('id')==0){
			$this->ajaxReturn(0, "密码错误", -3);
		}
		
		$info=M('user')->where(array('mobile' => $mobile))->find();
		
		$uid=$info['id'];//登录用户ID
		
		$islock=M('user')->where(array('id' => $uid))->getField('islock');
		if($islock==1){
			$this->ajaxReturn(0, "该用户被锁定，无法进行登录，请与管理员进行联系", -103);
		}
		
		$allMessage=0;
		
		//查找用户新赞数和新评论数
		$userinfo=M('user')->field('headicon,newsupportNum,newcommentNum')->where(array('id' => $uid))->find();
		
		$dialoginfo=M('dialog')->field('newLetterNum')->where("sendId=$uid or receiveId=$uid")->select();
		for($i=0;$i<count($dialoginfo);$i++){
			$allMessage+=intval($dialoginfo[$i]['newLetterNum']);
		}
		
		$allMessage+=intval($userinfo['newsupportNum']);
		$allMessage+=intval($userinfo['newcommentNum']);
		
		$returninfo=array();
		$returninfo['uid']=$uid;
		$returninfo['headicon']=headiconUrl($userinfo['headicon'], 60);
		$returninfo['allMessage']=$allMessage;

		//appKey : 
		//AppSecret : 
		//此处的AppKey  AppSecret 为融云SDK
		$p = new ServerAPI('','');
		$r = $p->getToken($uid,$info['username'],$returninfo['headicon']);
		//print_r($r);
		$returninfo['token'] = $r;


		$returninfo['userRefresh']=	$p->userRefresh($uid,$info['username'],$returninfo['headicon']);
		$returninfo['username'] = $info['username'];



		
		session('uid', $uid);
		$this->ajaxReturn($returninfo, "登录成功", 1);
	}
	
	//重置密码获取手机验证码
	public function getPhoneCode(){		
		
		$mobile=I('mobile');//手机号码
				
		//验证手机号码格式
		if(!isMobile($mobile)){
			 $this->ajaxReturn(0, "手机号码格式错误", -1);
		}
		//判断手机号码是否存在
		if(M('user')->where(array('mobile' => $mobile))->count('id')==0){
			$this->ajaxReturn(0, "手机号码不存在", -2);
		}
		//发送短信验证码
		$mobileCode=createMoibleCode();
		
		if(sendMobileCode($mobile, '验证码：'.$mobileCode)){
			session('mobile', $mobile);//注册手机号码传入session
			session('mobileCode', $mobileCode);//短信验证码传入session
			session('mobileCodeTime', time());//注册时间传入session
			$this->ajaxReturn(0, "操作成功", 1);
		}else{
			$this->ajaxReturn(0, "系统错误", -101);
		}
	}
	
	//重置密码 手机验证码确认
	public function verifyPhoneCode(){
	
		$phoneCode=I('phoneCode');//手机验证码
				
		//验证超时
		if(session('mobileCodeTime')+180<time()){
			$this->ajaxReturn(0, "180秒验证超时", -1);
		}
		//确认手机验证码
		if($phoneCode!=session('mobileCode')){
			$this->ajaxReturn(0, "验证码错误", -2);
		}
		
		$this->ajaxReturn(0, "验证成功", 1);
	}
	
	//重置密码操作
	public function resetPassword(){
		
		$password=I('password');//密码
		$confirmPassword=I('confirmPassword');//确认密码
		
		//判断密码长度
		if(strlen($password)<6 || strlen($password)>20){
			$this->ajaxReturn(0, "密码必须保持在6到20个字符之间", -1);
		}
		//判断确认密码
		if($password!=$confirmPassword){
			$this->ajaxReturn(0, "两次密码输入不一致", -2);
		}		
		
		$data=array(
			'password' => md5($password),
			'passwordTime' => time(),
		);
		
		if(M('user')->where(array('mobile' => session('mobile')))->save($data)){
			$this->ajaxReturn(0, "重置密码成功", 1);
		}else{
			$this->ajaxReturn(0, "系统错误", -101);
		}
		
	}



}
?>