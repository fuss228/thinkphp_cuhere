<?php
//用户管理
class UserAction extends CommonAction {

	//用户列表
	public function index(){
		import('Class.Page', APP_PATH);
		$count=M('user')->count();
		$page=new Page($count, 10);
		$limit=$page->firstRow.','.$page->listRows;
		$list=M('user')->field('id,headicon,username,mobile,islock')->order('id DESC')->limit($limit)->select();
		
		for($i=0;$i<count($list);$i++){
			$list[$i]['headicon']=headiconUrl($list[$i]['headicon'], 60);//用户头像转换
		}
		$this->list=$list;
		$this->page=$page->show();
		$this->display();
	}
	
	//用户详情
	public function detail(){
		$uid=I('uid', '0', 'intval');//用户ID
	
		$info=M('user')->field('id,headicon,username,mobile,brief')->where(array('id' => $uid))->find();
		$userinfo=M('userinfo')->field('realname,sex,website,constellation,province,city,area,citys,professional,company,businessCircle,officeBuildings,position')->where(array('uid' => $uid))->find();
		$info['userinfo']=$userinfo;
		$info['headicon']=headiconUrl($info['headicon'], 60);//用户头像转换
		$this->info=$info;
		$this->display();
	}
	
	//锁定用户
	public function lock(){
		$uid=I('uid', '0', 'intval');//用户ID
		
		$islock=M('user')->where(array('id' => $uid))->getField('islock');
		
		if($islock==1){
			//取消锁定
			if(M('user')->where(array('id' => $uid))->setField('islock', 0)){
				$this->success('取消锁定成功');
			}else{
				$this->error('取消锁定失败');
			}
		}else{
			//锁定用户
			if(M('user')->where(array('id' => $uid))->setField('islock', 1)){
				$this->success('锁定成功');
			}else{
				$this->error('锁定失败');
			}
		}
	}
}