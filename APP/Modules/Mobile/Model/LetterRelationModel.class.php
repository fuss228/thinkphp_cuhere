<?php
//私信模型
Class LetterRelationModel extends RelationModel{
	
	protected $tableName='letter';
	
	protected $_link=array(
		'senduser' => array(
			'mapping_type' => BELONGS_TO,
			'class_name' => 'user',
			'mapping_name' => 'senduser',
			'foreign_key' => 'uid',
			'mapping_fields' => 'headicon,username',
			'as_fields' => 'headicon,username',
		)
	);
	
	//获取私信的列表数据
	public function getUserLetters($where, $field='*', $order='id ASC'){		
		return $this->field($field)->where($where)->order($order)->relation(true)->select();
	}
	
	//获取私信的最后一条数据的信息的列表
	public function getUserInfoEnd($where, $field='*'){
		return $this->field($field)->where($where)->order('dialogId DESC')->group('dialogId')->relation(true)->select();
	}
}
?>