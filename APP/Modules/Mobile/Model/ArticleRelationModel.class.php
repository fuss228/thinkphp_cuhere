<?php
//帖子模型
Class ArticleRelationModel extends RelationModel{
	
	protected $tableName='article';
	
	protected $_link=array(
		'userinfo' => array(
			'mapping_type' => BELONGS_TO,
			'class_name' => 'user',
			'mapping_name' => 'userinfo',
			'foreign_key' => 'uid',
			'mapping_fields' => 'id,headicon,username',
		)
	);
	
	//获取主页帖子列表
	public function getArticleHomeList($where, $limit, $order='id DESC'){
		return $this->where($where)->limit($limit)->order($order)->relation(true)->select();
	}
	
	//获取帖子的详细内容
	public function getArticleDetail($where){
		return $this->where($where)->relation(true)->find();
	}
}
?>