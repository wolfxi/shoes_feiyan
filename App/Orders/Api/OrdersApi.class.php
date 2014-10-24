<?php

/**==============订单API中心================**/

namespace Orders\Api;
use Orders\Api\Api;
use Orders\Model\OrdersModel;
use Orders\Model\OrderstatusModel;
use Sample\Api\SampleApi;


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
		$list = $this->model->relation(true)->where($data)->order('o_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$result['page']=$show;
		$result['datalist']=$list;
		if($list && is_array($list)){
			$models=M();
			$datalist=array();
			foreach($result['datalist'] as $one){
				$datalist1=array();
				foreach($one['ordersdetail'] as $one_one){
					$one_one['imgurl']=$models->table("image")->where("s_id =%d",$one_one['s_id'])->getField("i_url");
					array_push($datalist1,$one_one);
				}
				$one['ordersdetail']=$datalist1;
				array_push($datalist,$one);
			}
			$result['datalist']=$datalist;
				
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
			$ordersdetail=array();
			foreach($result['ordersdetail'] as $one){
				$models=M();
				//获取图片
				$one['imgurl']=$models->table("image")->where("s_id= %d",$one['s_id'])->getField("i_url");
				$one['od_attribute']=unserialize($one['od_attribute']);
				//获取样品信息
				$one['sample']=$models->table("sample")->where("s_id = %d",$one['s_id'])->find();
				if($one['sample']['s_isproduce'] && count($one['od_attribute'])<=1){
					$one['od_attribute']=unserialize($one['sample']['s_attribute']);
				}
				array_push($ordersdetail,$one);	
			}

			$result['ordersdetail']=$ordersdetail;
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

		//订单总表信息
		$ordersstatus=C("ORDERS_STATUS");
		$orders['os_id']=$this->getOrdersStatus($ordersstatus['ORDERS_PRE'],null);	
		$orders['o_displayid']=$data['display_id'];
		unset($data['display_id']);
		$orders['oc_id']=$data['oc_id'];
		unset($data['oc_id']);
		$orders['o_customer']=$data['customer'];
		unset($data['customer']);
		$orders['o_number']=$data['number'];
		unset($data['number']);
		$orders['o_totalprice']=$data['totalprice'];
		unset($data['totalprice']);
		$orders['o_remark']=$data['remark'];
		unset($data['remark']);
		$orders['o_time']=date("Y-m-d :H:i:s");
		$orders['o_isdelete']=0;
		$orders['o_isproduce']=0;

		
		$models=M();
		$models->startTrans();
		$result1=$models->table("orders")->data($orders)->add();

		var_dump($models->_sql());

		//订单详细


		$datalist=array();
		foreach($data as $one){
			$temp['o_id']=$result1;
			$temp['s_id']=$one['s_id'];
			$temp['od_number']=$one['number'];
			$temp['od_bunchnum']=$one['bunchnum'];
			$temp['od_price']=$one['price'];
			$temp['od_sizes']=$one['sizes'];
			$temp['s_models']=$one['s_models'];
			$temp['od_totalprice']=$one['number']*$one['price'];
			$temp['od_isproduce']=0;

			array_push($datalist,$temp);
			unset($temp);
		}


		$result2=$models->table("ordersdetail")->addAll($datalist);
		if($result1 && $result2){
			$models->commit();
			return $result1;
		}else{
			$models->rollback();
			return FALSE; 
		$id=$this->model->data($orders)->add();
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
		
		$models=M();
		$models->startTrans();

		$ordersdetail=$data['ordersdetail'];
		unset($data['ordersdetail']);
		unset($data['o_id']);



		$flag1=$models->table("orders")->where("o_id = %d",intval($id))->data($data)->save();


		foreach($ordersdetail as $one){
			$od_id=$one['od_id'];
			unset($one['od_id']);
			unset($one['s_id']);
			$flag2=$models->table("ordersdetail")->where("od_id =%d",$od_id)->data($one)->save();
		}

		if($flag1 ||  $flag2){
			$models->commit();
			return true;
		}else{
			$models->rollback();
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
			$ordersstatus=C("ORDERS_STATUS");
			$os_id=$this->getOrdersStatus($ordersstatus['ORDERS_PRODUCE']);
			$orders['os_id']=$os_id;
			$orders['o_isproduce']=1;

			$flag=$this->model->where("o_id =%d",$id)->data($orders)->save();
			if($flag){
				return intval($flag);
			}else{
				return FALSE; 
			}
		}else{
			return FALSE; 
		}
	}


	/**
	 * 真正删除订单详情中的数据
	 * @param $data array
	 * return success true
	 * 			false false
	 */
	public function deleteOneOrderDetail($data){
		$models=M("ordersdetail");
		$flag=$models->where($data)->delete();
		if($flag){
			return true;
		}else{
			return false;
		}
	
	}


	/**
	 * 向已有的订单中添加新的鞋样
	 * @param $data 数据
	 * return array
	 * 		false false
	 */
	public function ajaxAddNewSample($data){

		$sampleapi=new SampleApi();
		$sample_result=$sampleapi->getOneSample($data['s_id']);

		//订单详情数据
		$orderdetail['o_id']=$data['o_id'];
		$orderdetail['s_id']=$sample_result['s_id'];
		$orderdetail['s_models']=$sample_result['s_models'];
		$orderdetail['od_isproduce']=0;
		
		$models=M();

		$flag=$models->table("ordersdetail")->where("o_id =%d AND s_id= %d",$data['o_id'],$data['s_id'])->find();
		if($flag){
			return false;
		}

		$od_id=$models->table("ordersdetail")->data($orderdetail)->add();
		if($od_id){
			$result['od_id']=$od_id;
			$result['sample']=$sample_result;
			return $result;
		}else{
			return false;
		}
	}



	/**
	 * 生成订单excel文件
	 * @param $time  数据处于什么时间段
	 * return  string   文件路劲
	 */
	public function createOrdersExcel($time){
		$map=array();
		switch ($time){
		case "month":
			$map['o_time']=array("LIKE",date("Y-m")."%");
			$name=date("Y-m")."月份的订单";
			break;
		case "prevmonth":
			$map['o_time']=array("LIKE",date("Y-m",strtotime("-1 month"))."%");
			$name=date("Y-m",strtotime("-1 month"))."月份的订单";
			break;
		case "prev3month":
			$map['o_time']=array("EGT",date("Y-m",strtotime("-3 month")));
			$name=date("Y-m",strtotime("-3 month"))."月份至".date("Y-m-d")."的订单";
			break;
		case "halfyear":
			$map['o_time']=array("EGT",date("Y-m",strtotime("-6 month")));
			$name="本半年的订单";
			break;
		case "oneyear":
			$map['o_time']=array("LIKE",date("Y-")."%");
			$name="本年的订单";
			break;
		case "prevyear":
			$map['o_time']=array("LIKE",date("Y-",strtotime("-1 year"))."%");
			$name="上一年的订单";
			break;
		case "all":
			$map=array();
			$name="所有的订单";
			break;
		default :
			$map=array();
			$name="所有的订单";
		}
		$result=$this->model->relation(true)->where($map)->select();
		if($result && is_array($result)){
			import('Vendor.PhpExcel.PHPExcel');
			$objPHPExcel= new \PHPExcel();
			$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
				->setLastModifiedBy("Maarten Balliauw")
				->setTitle("Office 2007 XLSX Test Document")
				->setSubject("Office 2007 XLSX Test Document")
				->setDescription("This document for Office 2007 XLSX.")
				->setKeywords("office 2007 ")
				->setCategory("office 2007");
			//设置表头
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', '订单编号')
				->setCellValue('B1', '样品型号')
				->setCellValue('C1', '订单客户')
				->setCellValue('D1', '订单装数')
				->setCellValue('E1', '订单双数')
				->setCellValue('F1', '订单码段')
				->setCellValue('G1', '订单单价')
				->setCellValue('H1', '订单金额')
				->setCellValue('J1', '订单状态')
				->setCellValue('I1', '订单时间');

			//设置内容数据
			$counter=2;
			foreach($result as $one){
				$sizes=unserialize($one['o_size']);
				sort($sizes);
				$min=$sizes[0];
				$max=$sizes[count($sizes)-1];
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$counter, $one['o_id'])
					->setCellValue('B'.$counter, $one['s_models'])
					->setCellValue('C'.$counter, $one['o_customer'])
					->setCellValue('D'.$counter, $one['o_bunchnum'])
					->setCellValue('E'.$counter, $one['o_number'])
					->setCellValue('F'.$counter, $min."-".$max)
					->setCellValue('G'.$counter, $one['o_price'])
					->setCellValue('H'.$counter, $one['o_totalprice'])
					->setCellValue('I'.$counter, $one['orderstatus']['os_name'])
					->setCellValue('J'.$counter, $one['o_time']);

				$counter++;
			}
			$objPHPExcel->getActiveSheet()->setTitle($name);
			Vendor("PhpExcel.PHPExcel.IOFactory");
			$objWriter =\PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$filename=time()."orders.xls";
			$path_name=C("DOCUMENT_SAVE_PATH").$filename;
			$objWriter->save($path_name);
			return $filename;

		}else{
			return false;
		}
	}





}



