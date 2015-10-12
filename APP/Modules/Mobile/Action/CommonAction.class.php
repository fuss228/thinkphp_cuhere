<?php
//公共操作模块
Class CommonAction extends Action{
	
	public function _initialize(){
		//session('uid', 10004);
		if(!isset($_SESSION['uid'])){
			$this->ajaxReturn(0, "无操作权限", -102);
		}
		$this->islock();
	}
	
	//判断用户是否被锁定
	public function islock(){
		$uid=session('uid');
		$islock=M('user')->where(array('id' => $uid))->getField('islock');
		if($islock==1){
			$this->ajaxReturn(0, "该用户被锁定，无法进行操作，请与管理员进行联系", -103);
		}
	}
}
?>