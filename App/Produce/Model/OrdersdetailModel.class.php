<?php
namespace Produce\Model;
use Think\Model\RelationModel;

class OrdersdetailModel extends RelationModel{

	protected $tableName='ordersdetail';
	protected $tablePrefix='';

	protected $_link=array(
		
		"followproduce"=>array(
			"mapping_type" 	=> self::HAS_ONE,
			"class_name" 	=> "Followproduce",
			"mapping_name"  => "followproduce",
			"foreign_key" 	=> "od_id"
		),

		"orders"=>array(
			"mapping_type" 	=> self::BELONGS_TO,
			"class_name" 	=> "Orders",
			"mapping_name" 	=> "orders",
			"foreign_key" 	=> "o_id"
		),

		"sample"=>array(
			"mapping_type" 	=>self::BELONGS_TO,
			"class_name" 	=>"Sample",
			"mapping_name" 	=>"sample",
			"foreign_key" 	=>"s_id"
		
		)
		


	);




}









?>
