<?php

/**==============订单API中心================**/

namespace Orders\Api;
use Orders\Api\Api;
use Orders\Model\OrdersModel;
use Orders\Model\OrderstatusModel;


class OrdersApi extends Api{


	//初始化（构造函数的一部分）
	//实例化用户模型
	function _init(){
		$this->model=new OrdersModel();	

	}

	/**
	 * 获取订单列表
	 * @param   $data  搜索条件
	 * return array 二维数组
	 */ 
	public function getOrdersList($data){
		$result=null;
		$count = $this->model->where($data)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(15)
		$show = $Page->show();// 分页显示输出
		$list = $this->model->relation("orderstatus")->where($data)->order('o_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$result['page']=$show;
		$result['datalist']=$list;
		if($list && is_array($list)){
			return $result;
		}else{
			return false;
		}
	}


	/**
	 * 获取订单详情
	 * return array  一维数组
	 * @param $id   订单编号
	 */
	public function getOneOrders($id){
		$result=$this->model->relation(true)->where("o_id = %d",intval($id))->find();
		if($result && is_array($result)){
			$imgmodel=M("image");
			$result['image']=$imgmodel->where("s_id = %d",intval($result['s_id']))->select();
			$result['o_size']=unserialize($result['o_size']);
			$result['o_attributes']=unserialize($result['o_attributes']);
			return $result;
		}else{
			return FALSE;
		}
	}


	/**
	 * 改变订单状态
	 * return boolean    
	 * 			success true
	 * 			flase   flase
	 * @param  $id   订单编号
	 */
	public function changOrderStatus($id,$status){

		$data['os_id']=intval($status);
		$flag=$this->model->where("o_id = %d", intval($id))->data($data)->save();
		return $flag;
	}


	/**
	 * 添加订单
	 * @param $data 
	 * 			数组 订单详情的信息
	 * 	return  
	 * 			success:	$id 新添加的订单的编号
	 * 			false: 	boolean   false
	 */
	public function addOrders($data){

		$orders['s_id']=$data['s_id'];
		$orders['os_id']=$this->getOrdersStatus("下单完结",null);	
		$orders['o_customer']=$data['customer'];
		$orders['o_bunchnum']=$data['bunchnum'];
		$orders['o_number']=$data['number'];
		$orders['o_price']=$data['price'];
		$orders['o_remark']=$data['remark'];
		$orders['o_totalprice']=floatval($data['price'])*intval($data['number']);
		$orders['o_time']=date("Y-m-d :H:i:s");
		$orders['o_size']=serialize($data['sizes']);
		$orders['s_models']=$data['models'];
		$orders['o_isdelete']=0;

		$orders['o_attributes']['sole']=$data['sole'];
		$orders['o_attributes']['shoes']=$data['shoes'];
		$orders['o_attributes']['shoesbag']=$data['shoesbag'];
		$orders['o_attributes']['insole']=$data['insole'];
		$orders['o_attributes']['innerbox']=$data['innerbox'];
		$orders['o_attributes']['outerbox']=$data['outerbox'];
		$orders['o_attributes']['other']=$data['other'];

		$orders['o_attributes']=serialize($orders['o_attributes']);

		$id=$this->model->data($orders)->add();
		if($id){
			return $id;
		}else{
			return FALSE; 
		} 
	}


	/**
	 * 修改订单信息
	 * @param $data 
	 * 			数组  修改的信息
	 * return 
	 * 			success:  ture
	 * 			flase: false 
	 */
	public function updateOrders($id,$data){
		$flag=$this->model->where("o_id = %d",intval($id))->data($data)->save();
		if($flag){
			return true;
		}else{
			return false;
		}
	}





	/**
	 * 获取订单状态字典数据
	 * return array   二维数组 / string /  id  
	 * @param $status_str 订单状态名称
	 */
	public function getOrdersStatus($status_str=null,$status_id=null){
		$result=null;
		$orderstatusmodel=new OrderstatusModel();
		if(!empty($status_str)){
			$result=$orderstatusmodel->getOneStatusByString($status_str );
		}elseif(!empty($status_id )){
			$result=$orderstatusmodel->getOneStatusById($status_id );
		}else{
			$result=$orderstatusmodel->getStatusList();
		}

		if($result){
			return $result;
		}else{
			return false;
		}

	}


	/**
	 * 订单投入生产 (生产跟踪表中新增一条记录）
	 * @param $id : 订单编号
	 * return boolean
	 * 			success true
	 * 			false false
	 */
	public function produceOrders($id){
		$result=$this->getOneOrders($id);
		if($result && is_array($result)){
			//改变订单状态
			$orderstatus=$this->getOrdersStatus("投入生产");
			$orders['os_id']=$orderstatus;

			$models=M();
			$models->startTrans();
			$flag0=$models->table("orders")->where("o_id = %d",$id)->data($orders)->save();

			//添加到生产跟踪单中
			$follow['o_id']=$result['o_id'];
			$follow['fp_starttime']=date("Y-m-d :H:i:s");
			$follow['fp_status']="投入生产";
			$follow['fp_progress']="";
			$follow['fp_logo']=$result['o_attributes']['shoes']['name'];
			$follow['fp_models']=$result['s_models'];
			$follow['fp_number']=$result['o_number'];
			$follow['fp_finishnum']=0;
			$follow['fp_totalnum']=$result['o_number'];

			$flag1=$models->table("followproduce")->data($follow)->add();

			if($flag0 && $flag1){
				$models->commit();
				return intval($flag1);
			}else{
				$models->rollback();
				return FALSE; 
			}
		}else{
			return FALSE; 
		}
	}








}



