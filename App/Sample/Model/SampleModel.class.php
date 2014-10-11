<?php
namespace Sample\Model;
use Think\Model\RelationModel;

class SampleModel extends RelationModel{

	protected $tableName='sample';
	protected $tablePrefix='';


	protected $_link=array(
		'image' => array(
			'mapping_type' => self::HAS_MANY,
			'mapping_name' => 'image',
			'foreign_key' => 's_id'
		)
	);




}









?>
