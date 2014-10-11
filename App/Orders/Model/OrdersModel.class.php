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

		"sample" 	=> array(
			'mapping_type' 	=>self::HAS_ONE,
			'mapping_name' 	=>"sample",
			'class_name' 	=>"Sample",
			'foreign_key' 	=>"s_id"
		)

	
	
	
	);




}









?>
