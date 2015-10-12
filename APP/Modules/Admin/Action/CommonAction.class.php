<?php
//后台初始化控制器
Class CommonAction extends Action{
	
	Public function _initialize(){
		if(!isset($_SESSION['managerid'])){
			$this->redirect('Admin/Login/index');
		}
	}
}
?>