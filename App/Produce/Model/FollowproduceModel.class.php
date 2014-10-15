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
			"foregin_key" 	=> "fp_id"
		)
		


	);

	/**
	 * 获取生产过程列表
	 * @param $data  array 
	 * return array 
	 * 		false false
	 */
	public function getFollowproduceList($data){
		$result=$this->relation("orders")->where($data)->select();
		if(!$result){
			return false;
		}
		//外包记录加入
		$result1=array();
		foreach($result as $one){
			$model=M();
			$one['epiboly']=$model->table("epiboly")->where("fp_id =%d",intval($one['fp_id']))->select();
			$shoesbag=unserialize($one['o_attributes']);
			foreach($one['epiboly'] as $one_one){
				if($one_one['e_models']==$shoesbag['shoesbag']['models']){
					$one['shoesbag']=$one_one;
				}else{
					$one['shoesbag']=array();
				}
			}
			//库存量加入
			$one['store']=$model->table("goodslist")->where("gl_models ='%s'",$one['fp_models'])->find();
			array_push($result1,$one);
		}

		return $result1;
	}



	/**
	 * 获取单个生产过程信息
	 * @param $data array
	 * return array
	 */
	public function getOneFollowproduce($data){
		$result=$this->relation("orders")->where($data)->find();
		if($result){
			//加入外包
			$models=M();
			$result['epiboly']=$models->table("epiboly")->where("fp_id = %d ",intval($result['fp_id']))->select();
			return $result;
		}else{
			return false;
		}
	
	}





}









?>
