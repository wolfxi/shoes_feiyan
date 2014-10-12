<?php
namespace Produce\Model;
use Think\Model\RelationModel;

class EpibolyModel extends RelationModel{

	protected $tableName='epiboly';
	protected $tablePrefix='';

	protected $_link=array(
		
		"followproduce"=>array(
			"mapping_type" 	=> self::BELONGS_TO,
			"class_name" 	=> "Followproduce",
			"mapping_name"  => "followproduce",
			"foreign_key" 	=> "fp_id"
		),

		"storerecord"=>array(
			"mapping_type" 	=> self::HAS_MANY,
			"class_name" 	=> "Storerecord",
			"mapping_name" 	=> "storerecord",
			"foregin_key" 	=> "e_id"
		)
		


	);




}









?>
