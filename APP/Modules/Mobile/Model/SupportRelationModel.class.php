<?php
//赞模型
Class SupportRelationModel extends RelationModel{
	
	protected $tableName='support';
	
	protected $_link=array(
		'userinfo' => array(
			'mapping_type' => BELONGS_TO,
			'class_name' => 'user',
			'mapping_name' => 'userinfo',
			'foreign_key' => 'uid',
			'mapping_fields' => 'id,headicon,username',
		)
	);
	
	//获取帖子的赞
	public function getArticleSupports($where, $limit, $order='id DESC'){
		return $this->where($where)->order($order)->limit($limit)->relation(true)->select();
	}
}
?>