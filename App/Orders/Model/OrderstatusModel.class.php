<?php
namespace Orders\Model;
use Think\Model;

class OrderstatusModel extends Model{

	protected $tableName='orderstatus';
	protected $tablePrefix='';

	//获取订单状态名称
	public function getOneStatusByString($status_str){
		$result=$this->where("os_name = '%s'",$status_str)->find();
		if($result && is_array($result)){
			return $result['os_id'];
		}else{
			return false;
		}
	}





	//获取订单状态编号	
	public function getOneStatusById($id){
		$result=$this->where("os_id = %d",intval($id))->find();
		if($result && is_array($result)){
			return $result['os_name'];
		}else{
			return false;
		}
	}

	//获取订单状态列表
	public function getStatusList(){
		$result=$this->select();
		if($result && is_array($result)){
			return $result;
		}else{
			return false;
		}
		
	}
















}









?>
