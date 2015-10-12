<?php
//帖子管理
class ArticleAction extends CommonAction {

	//帖子列表
	public function index(){
		import('Class.Page', APP_PATH);
		$count=M('article')->count();
		$page=new Page($count, 15);
		$limit=$page->firstRow.','.$page->listRows;
		$list=M('article')->field('id,pic,label,landmark,describe,toreport')->order('id DESC')->limit($limit)->select();
		
		for($i=0;$i<count($list);$i++){
			$list[$i]['pic']=pictureUrl($list[$i]['pic'], 212);//帖子图片转换
		}
		$this->list=$list;
		$this->page=$page->show();
		$this->display();
	}
	
	//被举报的帖子列表
	public function reportList(){
		import('Class.Page', APP_PATH);
		$count=M('article')->where(array('toreport' => 1))->count();
		$page=new Page($count, 12);
		$limit=$page->firstRow.','.$page->listRows;
		$list=M('article')->field('id,pic,label,landmark,describe,toreport')->where(array('toreport' => 1))->order('id DESC')->limit($limit)->select();
		
		for($i=0;$i<count($list);$i++){
			$list[$i]['pic']=pictureUrl($list[$i]['pic'], 212);//帖子图片转换
		}
		$this->list=$list;
		$this->page=$page->show();
		$this->display();
	}
	
	//帖子删除
	public function delete(){
		$aid=I('aid', '0', 'intval');//帖子ID
		
		//删除帖子
		if(M('article')->where(array('id' => $aid))->delete()){
			
			//删除帖子下的评论
			M('comments')->where(array('aid' => $aid))->delete();
			//删除帖子下的赞
			M('support')->where(array('aid' => $aid))->delete();
			//删除帖子下的举报
			M('toreport')->where(array('aid' => $aid))->delete();
		
			$this->success('帖子删除成功');
		}else{
			$this->error('帖子删除失败');
		}
	}
}