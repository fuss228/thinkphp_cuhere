<?php
//后台首页控制器
class IndexAction extends CommonAction {

	//后台首页视图
    public function index(){
		$this->display();
    }
	
	//退出操作
	public function logout(){
		session_unset();
		session_destroy();
		redirect(__GROUP__ . '/Login/index');
	}
}