<?php

namespace Admin\Controller;
use Think\Controller;
use Orders\Api\OrdersApi;
use Sample\Api\SampleApi;

class OrdersController extends AdminController{

	protected $ordersapi=null;

	public function _initialize(){
		parent::_initialize();

		$this->ordersapi=new OrdersApi();

	}


	/**
	 * 未完成的订单
	 */
	public function index(){
		$statusid=$this->ordersapi->getOrdersStatus("发货完结",null);
		$map['os_id']=array('NOT IN', $statusid);
		$map['o_isdelete']=array('EQ',0);
		$result=$this->ordersapi->getOrdersList($map);	
		if($result){
			$this->assign("datalist",$result['datalist']);
			$this->assign('page',$result['page']);
			$this->display();
		}else{
			$this->error('没有未完成的订单');	
		}
	}




	/**
	 * 已经完成的订单
	 */
	public function doneOrders(){
		$statusid=$this->ordersapi->getOrdersStatus("发货完结",null);
		$map['os_id']=array('IN',$statusid);
		$map['o_isdelete']=array('EQ',0);
		$result=$this->ordersapi->getOrdersList($map);	
		if($result){
			$this->assign("datalist",$result['datalist']);
			$this->assign('page',$result['page']);
			$this->display();
		}else{
			$this->error('没有未完成的订单');	
		}

	}



	/**
	 * 修改订单状态
	 */
	public function changeOrdersStatus(){

		$id=I("post.id");
		$status=I("post.status");
		if(empty($id) || empty($status)){
			$this->error("情选择订单状态");
		}else{
			$falg=$this->ordersapi->changOrderStatus($id,$status);
			if($flag){
				$this->redirect("/Orders/ordersDetail/",array('id'=>intval($id)));
			}else{
				$this->error("保存失败！！");
			}
		}
	}


	/**
	 * 查看订单详情
	 */
	public function ordersDetail(){

		$id=I("get.id");
		if(empty($id) || !intval($id)){
			$this->error("请选择你要查看的订单");
		}else{
			$result=$this->ordersapi->getOneOrders($id);
			if($result){
				$this->assign("orders",$result);
				$this->display();
			}else{
				$this->error("你查看的订单不存在！！！");
			}
		}
	}


	/**
	 * 跟踪的订单
	 */
	public function followOrders(){
		$statusid[]=$this->ordersapi->getOrdersStatus("投入生产",null);
		$statusid[]=$this->ordersapi->getOrdersStatus("生产完结",null);
		$map['os_id']=array('IN', $statusid);
		$map['o_isdelete']=array('EQ',0);
		$result=$this->ordersapi->getOrdersList($map);	
		if($result){
			$this->assign("datalist",$result['datalist']);
			$this->assign('page',$result['page']);
			$this->display();
		}else{
			$this->error('没有正在生产跟踪的订单');	
		}
	}









	/**
	 * 添加订单界面
	 */
	public function addOrdersUi(){
		$this->display();
	}

	/**
	 * 添加订单
	 */
	public function addOrders(){

		$data=I("post.");

		$check=true;
		foreach($data as $one){
			if(is_array($one)){
				foreach($one as $one_one){
					if(empty($one_one)){
						$check=false;
						break;
					}else{
						continue ;	
					}
				}
			}else{
				if(empty($one)){
					$check=false;
					break;
				}else{
					continue;
				}	
			}
			break;
		}

		if(!$check){
			$this->error("请填写订单详情");
		}
		$result=$this->ordersapi->addOrders($data);
		if($result){
			$this->redirect("/Orders/ordersDetail/",array('id'=>intval($result)));
		}else{
			$this->error("添加订单失败！！！");
		}
	}


	/**
	 * 只能AJAX调用
	 *根据样品型号获取样品信息
	 *并与AJAX的方式返回JSON格式的数据
	 *
	 */
	public function getSampleInfo(){
		if(IS_AJAX){
			$models=I("post.models");
			if(!empty($models) && is_string($models)){
				$sampleapi=new SampleApi();	
				$result=$sampleapi->getOneSample(null,$models);
				$result['s_attribute']=unserialize($result['s_attribute']);
				$data['flag']=true;
				$data['message']=$result;
				$this->ajaxReturn($data);
			}else{
				$data['flag']=false;
				$data['message']="样品型号不可以为空";	
				$this->ajaxReturn($data);
			}
		}else{
			exit();
		}
	}




	/**
	 * 将订单作废
	 */
	public function deleteOrders(){
		$id=I("get.id");
		if(empty($id) || !intval($id)){
			$this->error("请选择要删除的订单");
		}else{
			$data['o_isdelete']=1;
			$flag=$this->ordersapi->updateOrders($id,$data);
			if($flag){
				$this->success("删除成功！！！");
			}else{
				$this->error("删除失败！！！");
			}
		}
	}






	/**
	 * 修改订单界面
	 */
	public function updateOrdersUi(){
		$id=I("get.id");
		if(empty($id)){
			$this->error("选择要修改的订单");
		}else{
			$result=$this->ordersapi->getOneOrders($id);
			if($result && is_array($result)){
				$this->assign('orders',$result);
				$this->display();
			}else{
				$this->error("该订单不存在！！！");
			}
		}
	}


	/**
	 * 修改订单信息
	 */
	public function updateOrders(){
		$data=I("post.");

		$check=true;
		foreach($data as $one){
			if(is_array($one)){
				foreach($one as $one_one){
					if(empty($one_one)){
						$check=false;
						break;
					}else{
						continue ;	
					}
				}
			}else{
				if(empty($one)){
					$check=false;
					break;
				}else{
					continue;
				}	
			}
			break;
		}

		if(!$check){
			$this->error("请填写订单详情");
		}

		if(!empty($data['s_id'])){
			$orders['s_id']=$data['s_id'];
		}
		$orders['o_customer']=$data['customer'];
		$orders['o_bunchnum']=$data['bunchnum'];
		$orders['o_number']=$data['number'];
		$orders['o_price']=$data['price'];
		$orders['o_remark']=$data['remark'];
		$orders['o_totalprice']=floatval($data['price'])*intval($data['number']);
		$orders['o_size']=serialize($data['sizes']);
		$orders['s_models']=$data['models'];

		$orders['o_attributes']['sole']=$data['sole'];
		$orders['o_attributes']['shoes']=$data['shoes'];
		$orders['o_attributes']['shoesbag']=$data['shoesbag'];
		$orders['o_attributes']['insole']=$data['insole'];
		$orders['o_attributes']['innerbox']=$data['innerbox'];
		$orders['o_attributes']['outerbox']=$data['outerbox'];
		$orders['o_attributes']['other']=$data['other'];

		$orders['o_attributes']=serialize($orders['o_attributes']);



		$result=$this->ordersapi->updateOrders($data['o_id'],$orders);
		if($result){
			$this->redirect("/Orders/ordersDetail/",array('id'=>intval($data['o_id'])));
		}else{
			$this->error("修改订单失败！！！");
		}
	}


	/**
	 * 将订单投入生产
	 * 生成订单所需要的材料文档 
	 */
	public function produceOrders(){
		$id=I("post.o_id");	
		if(empty($id)){
			$this->error("请选择投入生产的订单");
		}else{
			$flag=$this->ordersapi->produceOrders($id);
			if($flag){
				$this->redirect("/Produce/getProduceMaterial/",array('id'=>intval($id)));
			}else{
				$this->error("投入生产失败");
			}
		}


	}


	/**
	 * 搜索订单信息
	 */
	public function searchOrders(){

		$search_type=I("post.search_type");
		$search_str=I("post.search_str");
		if(!empty($search_str) && !empty($search_type)){
			$map=array();
			if($search_type=="o_customer"){
				$map[$search_type]=array('LIKE',$search_str);
			}else{
				$map[$search_type]=array('EQ',$search_str);
			}
			$result=$this->ordersapi->getOrdersList($map);
			if($result){
				$this->assign("datalist",$result['datalist']);
				$this->assign("search","搜索".$search_type."类型下的".$search_str."结果如下：");
				$this->assign("page",$result['page']);
				$this->display();
			}else{
				$this->error("搜索内容不存在！！！");
			}

		}else{
			$this->error("请输入要搜索的内容");
		}
	}

	/**
	 *生成订单execl文件，（包括生成文件，下载文件)
	 */
	public function excelOrders(){
		if(IS_AJAX){
			$select_time=I("post.select_time");
			if(!empty($select_time)){
				$result=$this->ordersapi->createOrdersExcel($select_time);
				if($result && is_string($result)){
					$data['flag']=true;
					$data['message']=$result;
					$this->ajaxReturn($data);
				}else{
					$data['flag']=false;
					$data['message']="下载失败！！！";
					$this->ajaxReturn($data);
				}
			}else{
				$data['flag']=false;
				$data['message']="请选择要下载的订单！！！";
				$this->ajaxReturn($data);
			}
		}else{
			exit();
		}

	}


}
