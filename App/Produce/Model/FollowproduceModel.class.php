<?php
namespace Produce\Model;
use Think\Model\RelationModel;

class FollowproduceModel extends RelationModel{

	protected $tableName='followproduce';
	protected $tablePrefix='';

	protected $_link=array(
		
		"orders"=>array(
			"mapping_type" 	=> self::BELONGS_TO,
			"class_name" 	=> "Orders",
			"mapping_name"  => "orders",
			"foreign_key" 	=> "o_id"
		),

		"epiboly"=>array(
			"mapping_type" 	=> self::HAS_MANY,
			"class_name" 	=> "Epiboly",
			"mapping_name" 	=> "epiboly",
			"foregin_key" 	=> "fb_id"
		)
		


	);




}









?>
