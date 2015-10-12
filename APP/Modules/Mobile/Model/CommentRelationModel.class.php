<?php
//评论模型
Class CommentRelationModel extends RelationModel{
	
	protected $tableName='comments';
	
	protected $_link=array(
		'fromuser' => array(
			'mapping_type' => BELONGS_TO,
			'class_name' => 'user',
			'mapping_name' => 'fromuser',
			'foreign_key' => 'uid',
			'mapping_fields' => 'id,headicon,username',
		),
		'touser' => array(
			'mapping_type' => BELONGS_TO,
			'class_name' => 'user',
			'mapping_name' => 'touser',
			'foreign_key' => 'fid',
			'mapping_fields' => 'id,headicon,username',
		),
	);
	
	//获取帖子的评论
	public function getArticleComments($where, $limit, $order='id DESC'){
		return $this->where($where)->order($order)->limit($limit)->relation(true)->select();
	}
}
?>