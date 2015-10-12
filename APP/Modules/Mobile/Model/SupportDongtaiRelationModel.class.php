<?php
//赞动态模型
Class SupportDongtaiRelationModel extends RelationModel{
	
	protected $tableName='support';
	
	protected $_link=array(
		'userinfo' => array(
			'mapping_type' => BELONGS_TO,
			'class_name' => 'user',
			'mapping_name' => 'userinfo',
			'foreign_key' => 'uid',
			'mapping_fields' => 'id,headicon,username',
		),
		'articleinfo' => array(
			'mapping_type' => BELONGS_TO,
			'class_name' => 'article',
			'mapping_name' => 'articleinfo',
			'foreign_key' => 'aid',
			'mapping_fields' => 'id,pic',
		),
	);
	
	//获取赞动态列表
	public function getSupportsList($where, $limit, $order='id DESC'){
		return $this->where($where)->order($order)->limit($limit)->relation(true)->select();
	}
}
?>