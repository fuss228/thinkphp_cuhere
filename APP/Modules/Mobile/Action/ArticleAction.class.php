<?php
//帖子模块
Class ArticleAction extends CommonAction{
			
	//帖子发布
	public function release(){
		$uid=session('uid');//用户ID
		$label=I('label', '');//标签
		$landmark=I('landmark', '');//地标		
		$latitude=I('latitude', '0');//地标纬度
		$longitude=I('longitude', '0');//地标经度
		$describe=I('describe', '');//一句话
		
		$label=labelFilter($label);
						
		//图片上传
		import('ORG.Net.UploadFile');
		$upload=new UploadFile();
		//以日期格式创建子目录
		$upload->autoSub=true;
		$upload->subType="date";
		$upload->dateFormat="Ymd";
		$upload->allowExts=array('jpg', 'gif', 'png', 'jpeg');//设置文件上传类型
		$upload->thumb=true;//设置需要生成缩略图，仅对图像文件有效
		$upload->thumbPrefix='106_,212_,640_,1280_';//生产缩略图
		$upload->thumbMaxWidth='106,212,640,1280';//宽
		$upload->thumbMaxHeight='106,212,640,1280';//高
		$upload->thumbType='1';//缩略图生成方式 1 按设置大小截取 0 按原图等比例缩略
		$upload->thumbRemoveOrigin=false;//删除原图
		//图片上传成功
		if($upload->upload('./uploads/')){
			$info=$upload->getUploadFileInfo();
			$pic=$info[0]['savename'];
		}else{
			$this->ajaxReturn(0, $upload->getErrorMsg(), -1);//图片上传失败信息
		}
		
		//添加帖子
		$data=array(
			'uid' => $uid,
			'pic' => $pic,
			'label' => $label,
			'landmark' => $landmark,
			'latitude' => $latitude,
			'longitude' => $longitude,
			'describe' => $describe,
			'time' => time()
		);
		
		if($id=M('article')->add($data)){
			$this->ajaxReturn(array('aid' => $id), "发布成功", 1);
		}else{
			$this->ajaxReturn(0, "系统错误", -101);
		}
	}
	
	//帖子详细
	public function detail(){
	
		$aid=I('aid', '0', 'intval');//帖子ID
		
		//获取帖子的详细内容
		$field='id,uid,pic,label,landmark,describe,supportCount,commentCount,time,toreport';//查询帖子内容
		$info=D('ArticleRelation')->getArticleDetail(array('id' => $aid));
		if($info['time']<1422281700){
			$info['pic']=pictureUrl($info['pic'], 640);//用户上传图片转换
		}else{
			$info['pic']=pictureUrl($info['pic'], 1280);//用户上传图片转换
		}
		$info['time']=timeFriendly($info['time']);//上传时间转换
		$info['userinfo']['headicon']=headiconUrl($info['userinfo']['headicon'], 60);//用户头像转换
		
		//获取帖子的赞
		$supportlist=D('SupportRelation')->getArticleSupports(array('aid' => $aid));
		if($supportlistNum=count($supportlist)){
			for($i=0;$i<$supportlistNum;$i++){
				$supportlist[$i]['time']=timeFriendly($supportlist[$i]['time']);
				$supportlist[$i]['userinfo']['headicon']=headiconUrl($supportlist[$i]['userinfo']['headicon'], 60);//用户头像转换
			}
			$info['supportlist']=$supportlist;
		}else{
			$info['supportlist']='';
		}
		
		//获取帖子的评论
		$commontlist=D('CommentRelation')->getArticleComments(array('aid' => $aid));
		if($commontlistNum=count($commontlist)){
			for($i=0;$i<$commontlistNum;$i++){
				$commontlist[$i]['time']=timeFriendly($commontlist[$i]['time']);
				$commontlist[$i]['fromuser']['headicon']=headiconUrl($commontlist[$i]['fromuser']['headicon'], 60);//用户头像转换
				if(!$commontlist[$i]['touser']){
					$commontlist[$i]['touser']='';
				}else{
					$commontlist[$i]['touser']['headicon']=headiconUrl($commontlist[$i]['touser']['headicon'], 60);//用户头像转换
				}
			}
			$info['commontlist']=$commontlist;
		}else{
			$info['commontlist']='';
		}
		
		//是否赞
		if(inErArray(session('uid'), $info['supportlist'], 'uid')){
			$info['issupport']=1;
		}else{
			$info['issupport']=0;
		}
		$this->ajaxReturn($info, "帖子详细", 1);
	}
	
	//主页帖子列表
	public function homeList(){
		
		//分页设置
		$pageSize=20;//查询条数
		$currPage=I('page' , '1', 'intval');//获取当前页数
		$countPage=0;//总页数
	
		$uid=session('uid');//登录用户的ID标识
		
		$focuslist=array();
		//查找出用户所关注的人的列表ID
		$focus=M('focus')->field('fid')->where(array('uid' => $uid))->select();
		foreach($focus as $item){
			$focuslist[]=$item['fid'];
		}
		$focuslist[]=$uid;//用户及所关注的人的列表ID
		
		//查找显示帖子总数量
		$where=array('uid'=>array('IN', $focuslist));
		$countPage=M('article')->where($where)->count();
				
		//查找分页数据
		$limit=($currPage-1)*$pageSize.','.$pageSize;
		$list=D('ArticleRelation')->getArticleHomeList($where, $limit);
		
		for($i=0;$i<count($list);$i++){
			if($list[$i]['time']<1422281700){
				$list[$i]['pic']=pictureUrl($list[$i]['pic'], 640);//用户上传图片转换
			}else{
				$list[$i]['pic']=pictureUrl($list[$i]['pic'], 1280);//用户上传图片转换
			}
			$list[$i]['time']=timeFriendly($list[$i]['time']);//上传时间转换
			$list[$i]['userinfo']['headicon']=headiconUrl($list[$i]['userinfo']['headicon'], 60);//用户头像转换
			
			$where=array('aid' => $list[$i]['id']);
			//获取帖子的赞
			$supportlist=D('SupportRelation')->getArticleSupports($where);
			if($supportlistNum=count($supportlist)){
				for($num=0;$num<$supportlistNum;$num++){
					$supportlist[$num]['time']=timeFriendly($supportlist[$num]['time']);
					$supportlist[$num]['userinfo']['headicon']=headiconUrl($supportlist[$num]['userinfo']['headicon'], 60);//用户头像转换
				}				
				$list[$i]['supportlist']=$supportlist;
			}else{
				$list[$i]['supportlist']='';
			}
			//获取帖子的评论
			$commontlist=D('CommentRelation')->getArticleComments($where, 3);
			if($commontlistNum=count($commontlist)){
				for($num=0;$num<$commontlistNum;$num++){
					$commontlist[$num]['time']=timeFriendly($commontlist[$num]['time']);
					$commontlist[$num]['fromuser']['headicon']=headiconUrl($commontlist[$num]['fromuser']['headicon'], 60);//用户头像转换
					if(!$commontlist[$num]['touser']){
						$commontlist[$num]['touser']='';
					}else{
						$commontlist[$num]['touser']['headicon']=headiconUrl($commontlist[$num]['touser']['headicon'], 60);//用户头像转换
					}
				}
				$list[$i]['commontlist']=$commontlist;
			}else{
				$list[$i]['commontlist']='';
			}
			
			//是否赞
			if(inErArray(session('uid'), $list[$i]['supportlist'], 'uid')){
				$list[$i]['issupport']=1;
			}else{
				$list[$i]['issupport']=0;
			}
		}
		$this->ajaxReturn($list, "主页帖子列表", 1);
	}
	
	//点赞
	public function support(){
	
		$uid=session('uid');//用户ID		
		$aid=I('aid', '', 'intval');//帖子ID
		
		//判断帖子ID是否为空
		if(empty($aid)){
			$this->ajaxReturn(0, "帖子ID不能为空", -1);
		}
		
		if(M('support')->where(array('aid' => $aid, 'uid' => $uid))->count()>0){
			//取消点赞
			if(M('support')->where(array('aid' => $aid, 'uid' => $uid))->delete()){
				//点赞成功，帖子赞数加1
				M('article')->where(array('id' => $aid))->setDec('supportCount');
				$this->ajaxReturn(0, "取消点赞成功", 1);
			}else{
				$this->ajaxReturn(0, "系统错误", -101);
			}			
		}else{
			//点赞
			$data=array(
				'aid' => $aid,//帖子ID
				'uid' => $uid,//用户UID
				'time' => time()//点赞时间
			);
			
			if($id=M('support')->add($data)){
				//点赞成功，帖子赞数加1
				M('article')->where(array('id' => $aid))->setInc('supportCount');
				
				//点赞成功，用户的新赞数加1
				$articleUid=M('article')->where(array('id' => $aid))->getField('uid');//发布文章的用户ID
				M('user')->where(array('id' => $articleUid))->setInc('newsupportNum');
				
				$this->ajaxReturn(0, "点赞成功", 1);
			}else{
				$this->ajaxReturn(0, "系统错误", -101);
			}
		}
	}
	
	//赞列表
	public function supportList(){
		
		$aid=I('aid', '', 'intval');//帖子ID
		
		$list=D('SupportRelation')->getArticleSupports(array('aid' => $aid));
		if($listNum=count($list)){
			for($i=0;$i<$listNum;$i++){
				$list[$i]['time']=timeFriendly($list[$i]['time']);
				$list[$i]['userinfo']['headicon']=headiconUrl($list[$i]['userinfo']['headicon'], 60);//用户头像转换
			}
		}else{
			$list='';
		}
				
		$this->ajaxReturn($list, "帖子赞列表", 1);
	}
	
	//评论
	public function commont(){
	
		$aid=I('aid', '', 'intval');//帖子ID
		$comments=$_REQUEST['comments'];//评论内容
		
		if(empty($aid)){
			$this->ajaxReturn(0, "帖子ID不能为空", -1);
		}
		if(empty($comments)){
			$this->ajaxReturn(0, "评论内容不能为空", -2);
		}
		
		$data=array(
			'aid' => $aid,//帖子ID
			'fid' => I('fid', '0', 'intval'),//父用户ID
			'uid' => session('uid'),//用户UID
			'comments' => $comments,//评论内容
			'time' => time()//评论时间
		);
		
		if($id=M('comments')->add($data)){
			//评论成功，帖子评论数加1
			M('article')->where(array('id' => $aid))->setInc('commentCount');
			
			//评论成功，用户的新评论数加1
			$articleUid=M('article')->where(array('id' => $aid))->getField('uid');//发布文章的用户ID
			M('user')->where(array('id' => $articleUid))->setInc('newcommentNum');
			
			$this->ajaxReturn(0, "评论成功", 1);
		}else{
			$this->ajaxReturn(0, "系统错误", -101);
		}
	}
	
	//评论列表
	public function commontList(){
	
		$aid=I('aid', '', 'intval');//帖子ID
	
		//分页设置
		$pageSize=20;//查询条数
		$currPage=I('page' , '1', 'intval');//获取当前页数
		$countPage=0;//总页数
		
		//查找评论总数
		$countPage=M('comments')->where(array('aid' => $aid))->count();
		
		//查找分页数据
		$limit=($currPage-1)*$pageSize.','.$pageSize;
		$list=D('CommentRelation')->getArticleComments(array('aid' => $aid), $limit);
		if($listNum=count($list)){
			for($i=0;$i<$listNum;$i++){
				$list[$i]['time']=timeFriendly($list[$i]['time']);
				$list[$i]['fromuser']['headicon']=headiconUrl($list[$i]['fromuser']['headicon'], 60);//用户头像转换
				if(!$list[$i]['touser']){
					$list[$i]['touser']='';
				}else{
					$list[$i]['touser']['headicon']=headiconUrl($list[$i]['touser']['headicon'], 60);//用户头像转换
				}
			}
		}else{
			$list='';
		}
			
		$this->ajaxReturn($list, "帖子评论表", 1);
	}
	
	//搜索列表显示
	public function searchList(){
	
		//搜索条件
		$condition=I('condition', '');
		$where['label']=array('like', '%'.$condition.'%');

		//分页设置
		$pageSize=30;//查询条数
		$currPage=I('page' , '1', 'intval');//获取当前页数
		$countPage=0;//总页数
		
		//查找显示帖子总数量
		$countPage=M('article')->where($where)->count();
		
		//查找分页数据
		$limit=($currPage-1)*$pageSize.','.$pageSize;
		$list=M('article')->field('id,pic,toreport')->where($where)->order('id DESC')->limit($limit)->select();
		
		for($i=0;$i<count($list);$i++){
			$list[$i]['pic']=pictureUrl($list[$i]['pic'], 640);
		}
		
		$this->ajaxReturn($list, "搜索列表文章显示", 1);
	}
	
	//举报帖子
	public function toreport(){
	
		$uid=session('uid');//用户ID		
		$aid=I('aid', '', 'intval');//帖子ID
		
		//判断帖子ID是否为空
		if(empty($aid)){
			$this->ajaxReturn(0, "帖子ID不能为空", -1);
		}
		
		if(M('article')->where(array('id' => $aid))->setField('toreport', 1)){
			//举报成功
			$data=array(
				'aid' => $aid,//帖子ID
				'uid' => $uid,//用户UID
				'time' => time()//举报时间
			);
			M('toreport')->add($data);
			$this->ajaxReturn(0, "举报成功", 1);
		}
		$this->ajaxReturn(0, "举报失败", -1);
	}
	
	//帖子删除
	public function delete(){
		$aid=I('aid', '0', 'intval');//帖子ID
		$uid=session('uid');//登录用户ID
		
		//删除帖子
		if(M('article')->where(array('id' => $aid, 'uid' => $uid))->delete()){
			
			//删除帖子下的评论
			M('comments')->where(array('aid' => $aid))->delete();
			//删除帖子下的赞
			M('support')->where(array('aid' => $aid))->delete();
			//删除帖子下的举报
			M('toreport')->where(array('aid' => $aid))->delete();
		
			$this->ajaxReturn(0, "删除成功", 1);
		}else{
			$this->ajaxReturn(0, "删除失败", -1);
		}
	}
}
?>