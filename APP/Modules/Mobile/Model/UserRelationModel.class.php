<?php
//用户模型
Class UserRelationModel extends RelationModel{
	
	protected $tableName='user';
	
	protected $_link=array(
		'userinfo' => array(
			'mapping_type' => HAS_ONE,
			'class_name' => 'userinfo',
			'mapping_name' => 'userinfo',
			'foreign_key' => 'uid',
			'mapping_fields' => 'realname,website,constellation,sex,province,city,area,citys,professional,company,businessCircle,officeBuildings,position',
		)
	);
	
	//个人资料补填信息查询
	public function getUserinfoDetail($where, $field='*'){
		return $this->field($field)->where($where)->relation(true)->find();
	}
}
?>