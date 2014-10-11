<?php
namespace Storehouse\Model;
use Think\Model\RelationModel;

class GoodslistModel extends RelationModel{

	protected $tableName='goodslist';
	protected $tablePrefix='';

	protected $_link=array(

		"goodskind" 	=>array(
			"mapping_type" 	=> self::BELONGS_TO,
			"mapping_name" 	=> "goodskind",
			"class_name" 	=> "Goodskind",
			"foreign_key" 	=> "gk_id"
		)
	
	
	);




}









?>
