<?php
namespace Orders\Model;
use Think\Model\RelationModel;

class OrdersModel extends RelationModel{

	protected $tableName='orders';
	protected $tablePrefix='';


	protected $_link=array(
		
		"orderstatus" 	=> array(
			'mapping_type' 	=> self::BELONGS_TO,
			'class_name'   	=> "Orderstatus",
			'mapping_name' 	=> "orderstatus",
			'foreign_key' 	=> "os_id"
		),

		"ordersdetail" 	=>array(
			"mapping_type" 	=> self::HAS_MANY,
			"mapping_name" 	=>"ordersdetail",
			"class_name" 	=>"Ordersdetail",
			"foreign_key" 	=>"o_id"
		
		)


	
	
	
	);




}









?>
