<?php
//用户模块
Class UserAction extends CommonAction{

	//个人中心用户信息
	public function userinfo(){
		
		$uid=I('uid', '0', 'intval');//用户ID
		$field='id,username,headicon,brief,focusNum,fansNum,newFocusNum,newfansNum';
		$info=M('user')->field($field)->where(array('id' => $uid))->find();//查询用户信息
		$info['headicon']=headiconUrl($info['headicon'], 150);//用户头像转换
		
		$info['articleNum']=M('article')->where(array('uid' => $uid))->count();//帖子数量
		
		//用户家乡查询
		$info['position']=M('userinfo')->where(array('uid' => $uid))->getField('position');
		
		//当前登录用户
		if($uid!=session('uid')){
			$isfocus=M('focus')->where(array('fid' => $uid, 'uid' => session('uid')))->count();
			$info['isfocus']=$isfocus;
		}		
		$this->ajaxReturn($info, "用户中心信息返回", 1);
	}
	
	//获取用户头像
	public function userHeadicon(){
		$uid=I('uid', '0', 'intval');//用户ID
		$size=I('size', '60', 'intval');//头像大小
		$type=I('type', '0', 'intval');//获取头像类型
		$field='headicon';
		$info=M('user')->field($field)->where(array('id' => $uid))->find();//查询用户信息
		$info['headicon']=headiconUrl($info['headicon'], $size);//用户头像转换
		if($type==1){
			$soureImage=$info['headicon'];
			//判断图片类型
			$arrParam = explode ( '.', $info['headicon']);
			$type = strtolower ( end ( $arrParam ) );
			
			if ($type == "jpeg" || $type == "jpg") {
				header("Content-type: image/jpeg");
				$img = imagecreatefromjpeg ( $soureImage ); //JPG
				imagejpeg($img);
			} else if ($type == "gif") {
				header("Content-type: image/gif");
				$img = imagecreatefromgif ( $soureImage ); //GIF
				imagegif($img);
			} else if ($type == "png") {
				header("Content-type: image/png");
				$img = imagecreatefrompng ( $soureImage ); //PNG
				imagepng($img);
			}
			imagedestroy($img);
		}else{
			$this->ajaxReturn($info, "获取用户头像", 1);
		}
	}
	
	//关注功能
	public function focus(){
	
		$uid=session('uid');
		$fid=I('fid', '', 'intval');//被关注的用户ID
		if(empty($fid)){
			$this->ajaxReturn(0, "被关注的用户ID不能为空", -1);
		}
		
		//判断用户是否已经关注了该用户
		if(M('focus')->where(array('fid' => $fid, 'uid' => $uid))->count()>0){
			//用户已关注，取消关注
			if(M('focus')->where(array('fid' => $fid, 'uid' => $uid))->delete()){
				//将登录用户的关注数减1
				M('user')->where(array('id' => $uid))->setDec('focusNum');
				//将当前用户的粉丝数减1
				M('user')->where(array('id' => $fid))->setDec('fansNum');
				
				//关注成功，新关注数加1
				M('user')->where(array('id' => $uid))->setDec('newFocusNum');
				//关注成功，新粉丝数加1
				M('user')->where(array('id' => $fid))->setDec('newfansNum');
				
				$this->ajaxReturn(0, "取消关注成功", 1);
			}else{
				$this->ajaxReturn(0, "系统错误", -101);
			}
		}else{
			//用户没有关注，建立关注关系
			$data=array(
				'fid' => $fid,//被关注的用户ID
				'uid' => $uid,//用户UID
				'time' => time()//关注时间
			);
			
			if($id=M('focus')->add($data)){
				//关注成功，关注数加1
				M('user')->where(array('id' => $uid))->setInc('focusNum');
				//关注成功，粉丝数加1
				M('user')->where(array('id' => $fid))->setInc('fansNum');
				
				//关注成功，新关注数加1
				M('user')->where(array('id' => $uid))->setInc('newFocusNum');
				//关注成功，新粉丝数加1
				M('user')->where(array('id' => $fid))->setInc('newfansNum');
				
				
				$this->ajaxReturn(0, "关注成功", 1);
			}else{
				$this->ajaxReturn(0, "系统错误", -101);
			}
		}
	}
	
	//个人资料补填用户信息反馈
	public function userinfoShow(){
		
		$uid=session('uid');//当前用户ID
		
		$userinfo=D('UserRelation')->getUserinfoDetail(array('id' => $uid), 'id,mobile,username,headicon,brief,privacy');				
		$userinfo['headicon']=headiconUrl($userinfo['headicon'], 60);//用户头像转换
		$this->ajaxReturn($userinfo, "个人资料补填用户信息反馈", 1);
	}
	
	//个人头像重写
	public function headiconReset(){
		
		$uid=session('uid');//当前用户ID
		
		//图片上传
		import('ORG.Net.UploadFile');
		$upload=new UploadFile();
		//以日期格式创建子目录
		$upload->autoSub=true;
		$upload->subType="date";
		$upload->dateFormat="Ymd";
		$upload->allowExts=array('jpg', 'gif', 'png', 'jpeg');//设置文件上传类型
		$upload->thumb=true;//设置需要生成缩略图，仅对图像文件有效
		$upload->thumbPrefix='60_,150_,640_';//生产缩略图
		$upload->thumbMaxWidth='60,150,640';//宽
		$upload->thumbMaxHeight='60,150,640';//高
		$upload->thumbType='1';//缩略图生成方式 1 按设置大小截取 0 按原图等比例缩略
		$upload->thumbRemoveOrigin=false;//删除原图
		//图片上传成功
		if($upload->upload('./Uploads/headicon/')){
			$info=$upload->getUploadFileInfo();
			$pic=$info[0]['savename'];
		}else{
			$this->ajaxReturn(0, $upload->getErrorMsg(), -1);//图片上传失败信息
		}
		
		$data=array(
			'id' => $uid,
			'headicon' => $pic
		);
		
		if(M('user')->save($data)){
			$headicon=headiconUrl($pic, 60);//用户头像转换($pic, 60);
			$this->ajaxReturn(array('headicon' => $headicon), "用户头像修改成功", 1);
		}else{
			$this->ajaxReturn(0, "系统错误", -101);
		}
	}
	
	//隐私设置重写
	public function privacyReset(){
		$uid=session('uid');
		
		if(M('user')->where(array('id' => $uid))->getField('privacy')==0){
			//隐私为打开状态，则关闭
			M('user')->where(array('id' => $uid))->setField('privacy', 1);
			$this->ajaxReturn(0, "用户隐私关闭状态", 1);
		}else{
			M('user')->where(array('id' => $uid))->setField('privacy', 0);
			$this->ajaxReturn(0, "用户隐私打开状态", 2);
		}
	}
	
	//密码重写
	public function passwordReset(){
		$uid=session('uid');
		$oldPassword=I('oldPassword', '', 'md5');//旧密码
		$newPassword=I('newPassword');//新密码
		$confirmPassword=I('confirmPassword');//确认密码
		
		//判断密码长度
		if(strlen($newPassword)<6 || strlen($newPassword)>20){
			$this->ajaxReturn(0, "密码必须保持在6到20个字符之间", -1);
		}
		//判断确认密码
		if($newPassword!=$confirmPassword){
			$this->ajaxReturn(0, "两次密码输入不一致", -2);
		}
		//判断原始密码
		if(M('user')->where(array('id' => $uid, 'password' => $oldPassword))->count()==0){
			$this->ajaxReturn(0, "初始密码错误", -3);
		}
		
		$data=array(
			'id' => $uid,
			'password' => md5($newPassword),
			'passwordTime' => time(),
		);
		
		if(M('user')->save($data)){
			$this->ajaxReturn(0, "密码修改成功", 1);
		}else{
			$this->ajaxReturn(0, "系统错误", -101);
		}
		
	}
	
	//个人资料重写
	public function userinfoReset(){
	
		$uid=session('uid');
		
		$dataUser=array(
			'brief' => I('brief', '')
		);
		
		$dataUserinfo=array(
			'realname' => I('realname', ''),
			'website' => I('website', ''),
			'constellation' => I('constellation', ''),
			'sex' => I('sex', '0', 'intval'),
			'province' => I('province', ''),
			'city' => I('city', ''),
			'area' => I('area', ''),
			'citys' => I('citys', ''),
			'professional' => I('professional', ''),
			'company' => I('company', ''),
			'businessCircle' => I('businessCircle', ''),
			'officeBuildings' => I('officeBuildings', ''),
			'position' => I('position', ''),
			'time' => time()
		);
		
		if(M('userinfo')->where(array('uid' => $uid))->save($dataUserinfo)){
			M('user')->where(array('id' => $uid))->save($dataUser);
			$this->ajaxReturn(0, "个人资料修改成功", 1);
		}else{
			$this->ajaxReturn(0, "系统错误", -101);
		}
	}
	
	//个人中心帖子九宫格
	public function picList(){
	
		//分页设置
		$pageSize=9;//查询条数
		$currPage=I('page' , '1', 'intval');//获取当前页数
		$countPage=0;//总页数
		
		$uid=I('uid' , '', 'intval');//用户ID
		
		//查找显示帖子总数量
		$countPage=M('article')->where(array('uid' => $uid))->count();
				
		//查找分页数据
		$limit=($currPage-1)*$pageSize.','.$pageSize;
		$list=M('article')->field('id,pic,toreport')->where(array('uid' => $uid))->order('id DESC')->limit($limit)->select();
		
		for($i=0;$i<count($list);$i++){
			$list[$i]['pic']=pictureUrl($list[$i]['pic'], 212);
		}
		$this->ajaxReturn($list, "个人中心帖子九宫格列表", 1);
	}
	
	//个人中心帖子列表
	public function articleList(){
	
		//分页设置
		$pageSize=20;//查询条数
		$currPage=I('page' , '1', 'intval');//获取当前页数
		$countPage=0;//总页数
	
		$uid=I('uid' , '', 'intval');//用户ID
		
		//查找显示帖子总数量
		$countPage=M('article')->where(array('uid' => $uid))->count();
				
		//查找分页数据
		$limit=($currPage-1)*$pageSize.','.$pageSize;
		$list=D('ArticleRelation')->getArticleHomeList(array('uid' => $uid), $limit);
		//插入赞及评论表
		for($i=0;$i<count($list);$i++){
			$list[$i]['pic']=pictureUrl($list[$i]['pic'], 640);//用户上传图片转换
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
			$commontlist=D('CommentRelation')->getArticleComments($where, 2);
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
			if(inErArray(session('uid'), $info['supportlist'], 'uid')){
				$info['issupport']=1;
			}else{
				$info['issupport']=0;
			}
		}
		$this->ajaxReturn($list, "个人中心帖子列表", 1);
	}
	
	//关注列表
	public function focusList(){		
		//分页设置
		$pageSize=50;//查询条数
		$currPage=I('page' , '1', 'intval');//获取当前页数
		$countPage=0;//总页数
		
		$uid=I('uid' , '', 'intval');//用户ID
		
		//将用户的新关注数设置为0
		M('user')->where(array('id' => $uid))->setField('newFocusNum', 0);
		
		$focuslist=array();
		//查找出用户所关注的人的列表ID
		$focus=M('focus')->field('fid')->where(array('uid' => $uid))->select();
		foreach($focus as $item){
			$focuslist[]=$item['fid'];
		}
		
		//查找显示总数量
		$countPage=count($focuslist);
		
		//查找分页数据
		$where=array('id'=>array('IN', $focuslist));
		$limit=($currPage-1)*$pageSize.','.$pageSize;
		$list=M('user')->field('id,username,headicon')->where($where)->order('id DESC')->limit($limit)->select();
		
		for($i=0;$i<count($list);$i++){
			$list[$i]['headicon']=headiconUrl($list[$i]['headicon'], 60);//用户头像转换
		}
		$this->ajaxReturn($list, "关注列表", 1);
	}
	
	//粉丝列表
	public function fansList(){
		//分页设置
		$pageSize=50;//查询条数
		$currPage=I('page' , '1', 'intval');//获取当前页数
		$countPage=0;//总页数
		
		$uid=I('uid' , '', 'intval');//用户ID
		
		//将用户的新粉丝数设置为0
		M('user')->where(array('id' => $uid))->setField('newfansNum', 0);
		
		$focuslist=array();
		//查找出用户被关注的人的列表ID
		$focus=M('focus')->field('uid')->where(array('fid' => $uid))->select();
		foreach($focus as $item){
			$focuslist[]=$item['uid'];
		}
		
		//查找显示总数量
		$countPage=count($focuslist);
		
		//查找分页数据
		$where=array('id'=>array('IN', $focuslist));
		$limit=($currPage-1)*$pageSize.','.$pageSize;
		$list=M('user')->field('id,username,headicon')->where($where)->order('id DESC')->limit($limit)->select();
		
		for($i=0;$i<count($list);$i++){
			$list[$i]['headicon']=headiconUrl($list[$i]['headicon'], 60);//用户头像转换
		}
		$this->ajaxReturn($list, "粉丝列表", 1);
	}
	
	//搜索列表显示
	public function searchList(){
	
		//搜索条件
		$condition=I('condition', '');
		$where['username']=array('like', '%'.$condition.'%');
		$where['brief']=array('like','%'.$condition.'%');
		$where['_logic'] = 'or';
		$mapCondition['_complex'] = $where;

		//分页设置
		$pageSize=20;//查询条数
		$currPage=I('page' , '1', 'intval');//获取当前页数
		$countPage=0;//总页数
		
		//查找显示帖子总数量
		$countPage=M('user')->where($mapCondition)->count();
		
		//查找分页数据
		$limit=($currPage-1)*$pageSize.','.$pageSize;
		$list=M('user')->field('id,username,headicon')->where($mapCondition)->order('id DESC')->limit($limit)->select();
		
		for($i=0;$i<count($list);$i++){
			$list[$i]['headicon']=headiconUrl($list[$i]['headicon'], 60);
		}
		
		$this->ajaxReturn($list, "搜索列表用户显示", 1);
	}
	
	//私信界面信息展示
	public function letterShow(){
		$sendId=session('uid');//当前登录用户ID
		$receiveId=I('receiveId' , '', 'intval');//接收私信用户ID
		$first=I('first', 0, 'intval');//第一次读取接口
		
		//查找该对用户是否存在对话
		$where['_string']="(sendId=$sendId and receiveId=$receiveId) or (sendId=$receiveId and receiveId=$sendId)";
		$dialogId=M('dialog')->where($where)->getField('id');//查找对话ID
				
		//私聊对话不存在，创建私聊对话
		if(!$dialogId){
			$data=array(
				'sendId' => $sendId,
				'receiveId' => $receiveId
			);
			$dialogId=M('dialog')->add($data);
		}
		
		//设置对话ID
		$info['dialogId']=$dialogId;
		
		//设置私聊标题
		$info['title']=M('user')->where(array('id' => $receiveId))->getField('username');
		
		if($first==1){
			//第一次进入，读取旧的消息列表
			$list=D('LetterRelation')->field('id,uid,content,time,isread')->where(array('dialogId' => $dialogId))->order('id ASC')->relation(true)->select();
			if($listNum=count($list)){
				for($i=0;$i<count($list);$i++){
					$list[$i]['headicon']=headiconUrl($list[$i]['headicon'], 60);//用户头像
					$list[$i]['time']=timeFriendly($list[$i]['time'], 2);//时间友好化
					if($list[$i]['uid']==$sendId){
						$list[$i]['type']=2;
					}else{
						$list[$i]['type']=1;
					}
					//查看帖子是否已被阅读
					if($list[$i]['isread']!=1){
						//当前私信置为已读
						M('letter')->where(array('id' => $list[$i]['id']))->setField('isread', 1);
					}
				}
				$info['list']=$list;
			}else{
				$info['list']='';
			}
			$this->ajaxReturn($info, "第一次进入房间获取旧的消息", 1);			
		}
		
		//查找当前对话的内容
		$list=D('LetterRelation')->getUserLetters(array('dialogId' => $dialogId, 'uid' => $receiveId, 'isread' => 0), 'id,uid,content,time');
		
		if($listNum=count($list)){
			for($i=0;$i<count($list);$i++){
				$list[$i]['headicon']=headiconUrl($list[$i]['headicon'], 60);//用户头像
				$list[$i]['time']=timeFriendly($list[$i]['time'], 2);//时间友好化
				if($list[$i]['uid']==$sendId){
					$list[$i]['type']=2;
				}else{
					$list[$i]['type']=1;
				}
				//当前私信置为已读
				M('letter')->where(array('id' => $list[$i]['id']))->setField('isread', 1);
			}
			$info['list']=$list;
		}else{
			$info['list']='';
		}		
		
		$this->ajaxReturn($info, "私信界面信息展示", 1);
	}
	
	//发送私聊内容
	public function sendLetter(){		
		$dialogId=I('dialogId' , '', 'intval');//对话ID				
		$sendId=session('uid');//当前登录用户ID
		$content=$_REQUEST['content'];//私信内容
		
		if(empty($dialogId)){
			$this->ajaxReturn(0, "对话ID不能为空", -1);
		}
		if(empty($content)){
			$this->ajaxReturn(0, "内容不能为空", -2);
		}
		
		$data=array(
			'dialogId' => $dialogId,//对话ID
			'uid' => $sendId,//发送私信用户ID
			'content' => $content,//私信内容
			'time' => time()//私信时间
		);
		
		if($id=M('letter')->add($data)){
			
			//发送私信成功，则在对话中将新私信数加1
			M('dialog')->where(array('id' => $dialogId))->setInc('newLetterNum');
			
			$this->ajaxReturn(0, "私信发送成功", 1);
		}else{
			$this->ajaxReturn(0, "系统错误", -101);
		}
	}
	
	//消息列表
	public function messageList(){
		$uid=session('uid');//当前登录用户ID
		
		//查找该对用户是否存在对话
		$where['_string']="(sendId=$uid) or (receiveId=$uid)";
		$dialogList=M('dialog')->where($where)->order('id DESC')->select();
		
		$list=array();
		
		for($i=0;$i<count($dialogList);$i++){
			$dialoginfo=M('letter')->where(array('dialogId' => $dialogList[$i]['id']))->order('id DESC')->find();
			if($dialoginfo){
				$dialogList[$i]['content']=$dialoginfo['content'];
				$dialogList[$i]['time']=timeFriendly($dialoginfo['time']);
				$list[]=$dialogList[$i];
			}
		}
		
		for($i=0;$i<count($list);$i++){
			if($list[$i]['sendId']==$uid){
				$roomuid=$list[$i]['receiveId'];
			}else{
				$roomuid=$list[$i]['sendId'];
			}			
			$userinfo=M('user')->where(array('id' => $roomuid))->find();
			$list[$i]['headicon']=headiconUrl($userinfo['headicon'], 60);
			$list[$i]['username']=$userinfo['username'];
		}
		
		$info=array();
		if(count($list)==0){
			$info['list']='';
		}else{
			$info['list']=$list;
		}
		
		//用户新赞数
		$info['newsupportNum']=M('user')->where(array('id' => $uid))->getField('newsupportNum');
		//用户新评论数
		$info['newcommentNum']=M('user')->where(array('id' => $uid))->getField('newcommentNum');
		
		$this->ajaxReturn($info, "消息列表", 1);
	}
	
	//对话房间
	public function messageRoom(){
		$dialogId=I('dialogId' , '', 'intval');//对话ID
		$uid=session('uid');//登录用户ID
		$first=I('first', 0, 'intval');//第一次读取接口
		
		//将该对话房间的私信新增数设置为0
		M('dialog')->where(array('id' => $dialogId))->setField('newLetterNum', 0);
						
		//设置对话ID
		$info['dialogId']=$dialogId;
		
		//设置私聊标题
		$dialogInfo=M('dialog')->where(array('id' => $dialogId))->find();//对话信息
		if($uid==$dialogInfo['sendId']){
			$receiveId=$dialogInfo['receiveId'];
		}else{
			$receiveId=$dialogInfo['sendId'];
		}
		$info['title']=M('user')->where(array('id' => $receiveId))->getField('username');		
		
		if($first==1){
			//第一次进入，读取旧的消息列表
			$list=D('LetterRelation')->field('id,uid,content,time,isread')->where(array('dialogId' => $dialogId))->order('id ASC')->relation(true)->select();
			if($listNum=count($list)){
				for($i=0;$i<count($list);$i++){
					$list[$i]['headicon']=headiconUrl($list[$i]['headicon'], 60);//用户头像
					$list[$i]['time']=timeFriendly($list[$i]['time'], 2);//时间友好化
					if($list[$i]['uid']==$uid){
						$list[$i]['type']=2;
					}else{
						$list[$i]['type']=1;
					}
					//查看帖子是否已被阅读
					if($list[$i]['isread']!=1){
						//当前私信置为已读
						M('letter')->where(array('id' => $list[$i]['id']))->setField('isread', 1);
					}
				}
				$info['list']=$list;
			}else{
				$info['list']='';
			}
			$this->ajaxReturn($info, "第一次进入房间获取旧的消息", 1);			
		}
		
		//查找当前对话的内容
		$list=D('LetterRelation')->getUserLetters(array('dialogId' => $dialogId, 'uid' => $receiveId, 'isread' => 0), 'id,uid,content,time');
				
		if($listNum=count($list)){
			for($i=0;$i<count($list);$i++){
				$list[$i]['headicon']=headiconUrl($list[$i]['headicon'], 60);//用户头像
				$list[$i]['time']=timeFriendly($list[$i]['time'], 2);//时间友好化
				if($list[$i]['uid']==$uid){
					$list[$i]['type']=2;
				}else{
					$list[$i]['type']=1;
				}
				
				//当前私信置为已读
				M('letter')->where(array('id' => $list[$i]['id']))->setField('isread', 1);
			}
			$info['list']=$list;
		}else{
			$info['list']='';
		}
		
		$this->ajaxReturn($info, "对话房间", 1);
	}
	
	//赞动态列表
	public function supportList(){
	
		//分页设置
		$pageSize=40;//查询条数
		$currPage=I('page' , '1', 'intval');//获取当前页数
		$countPage=0;//总页数
	
		$uid=session('uid');//登录用户的ID标识
		
		//将用户的新赞数设置为0
		M('user')->where(array('id' => $uid))->setField('newsupportNum', 0);
		
		$articleIdList=array();
		//查找出这个用户所发的帖子的ID
		$articleId=M('article')->field('id')->where(array('uid' => $uid))->select();
		foreach($articleId as $item){
			$articleIdList[]=$item['id'];
		}
		
		//查找赞动态总数量
		$where=array('aid'=>array('IN', $articleIdList));
		$countPage=M('support')->where($where)->count();
		
		//查找分页数据
		$limit=($currPage-1)*$pageSize.','.$pageSize;
		$list=D('SupportDongtaiRelation')->getSupportsList($where, $limit);
		
		for($i=0;$i<count($list);$i++){
			$list[$i]['time']=timeFriendly($list[$i]['time']);//上传时间转换
			$list[$i]['userinfo']['headicon']=headiconUrl($list[$i]['userinfo']['headicon'], 60);//用户头像转换
			$list[$i]['articleinfo']['pic']=pictureUrl($list[$i]['articleinfo']['pic'], 106);//用户上传图片转换
		}
		$this->ajaxReturn($list, "赞动态列表", 1);
	}
	
		
	//评论动态列表
	public function commentList(){
	
		//分页设置
		$pageSize=40;//查询条数
		$currPage=I('page' , '1', 'intval');//获取当前页数
		$countPage=0;//总页数
	
		$uid=session('uid');//登录用户的ID标识
		
		//将用户的新评论数设置为0
		M('user')->where(array('id' => $uid))->setField('newcommentNum', 0);
		
		$articleIdList=array();
		//查找出这个用户所发的帖子的ID
		$articleId=M('article')->field('id')->where(array('uid' => $uid))->select();
		foreach($articleId as $item){
			$articleIdList[]=$item['id'];
		}		
		
		//查找赞动态总数量
		$where=array('aid'=>array('IN', $articleIdList));
		$countPage=M('comments')->where($where)->count();
		
		//查找分页数据
		$limit=($currPage-1)*$pageSize.','.$pageSize;
		$list=D('CommentDongtaiRelation')->getCommentsList($where, $limit);
		
		for($i=0;$i<count($list);$i++){			
			$list[$i]['time']=timeFriendly($list[$i]['time']);//上传时间转换
			$list[$i]['userinfo']['headicon']=headiconUrl($list[$i]['userinfo']['headicon'], 60);//用户头像转换
			$list[$i]['articleinfo']['pic']=pictureUrl($list[$i]['articleinfo']['pic'], 106);//用户上传图片转换
		}
		
		$this->ajaxReturn($list, "评论动态列表", 1);
	}
	
	//退出登录
	public function logout(){
		session_unset();
		session_destroy();
		$this->ajaxReturn(0, "退出成功", 1);
	}
}
?>