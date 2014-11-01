<?php

namespace Admin\Controller;
use Think\Controller;
use Produce\Api\ProduceApi;
use Orders\Api\OrdersApi;

class ProduceController extends AdminController{

	protected $produceapi=null;
	protected $ordersapi=null;
	function _initialize(){

		parent::_initialize();
		$this->produceapi=new ProduceApi();
		$this->ordersapi=new OrdersApi();
		$this->assign("imgurl",C("UPLOADIMG_URL"));
	}

	/**
	 * 现在生产着的单子
	 */
	public function index(){

		$ordersstatus=C('ORDERS_STATUS');
		$os_id=$this->ordersapi->getOrdersStatus($ordersstatus['ORDERS_PRODUCE']);
		$map['os_id']=$os_id;
		$map['o_isproduce']=1;
		$map['o_isdelete']=0;
		$result=$this->ordersapi->getOrdersList($map);
		if($result){
			$this->assign("datalist",$result['datalist']);
			$this->assign("page",$result['page']);
			$this->display();
		}else{
			$this->error("还没有生产的单子");
		}
	}


	/**
	 * 待发货的单
	 */
	public function waitSendGoodsList(){
		$orderstatus=C("ORDERS_STATUS");
		$os_id=$this->ordersapi->getOrdersStatus($orderstatus['ORDERS_OK']);
		$orders['os_id'] = array("EQ",$os_id);
		$orders['o_isdelete'] = array("EQ",0);
		$orders['o_isproduce'] = array("EQ",1);
		$result=$this->ordersapi->getOrdersList($orders);
		if($result){
			$this->assign("datalist",$result['datalist']);
			$this->assign("page",$result['page']);
			$this->display();
		}else{
			$this->error("还没有要发货的单子");
		}
	}




	/**
	 * 产看生产单详情
	 */
	public function produceDetail(){
		$id=I("get.id");
		if(!empty($id)){
			$result=$this->ordersapi->getOneOrders($id);
			if($result && is_array($result)){
				$this->assign("result",$result);
				$this->assign("orderstatus",C("ORDERS_STATUS"));
				$this->display();
			}else{
				$this->error("获取信息失败！！！");
			}	
		}else{
			$this->error("请选择要查看的单子");
		}
	}


	/**
	 * 获取一个订单的跟踪信息
	 */
	public function followProduce(){
		$o_id=I("get.id");	
		if(!empty($o_id)){
			$data['o_id']=$o_id;
			$result=$this->produceapi->getFollowProduce($data);
			if($result && is_array($result)){
				$this->assign("orders",$result['orders']);
				$this->assign("ordersdetail",$result['ordersdetail']);
				$this->display();
			
			}else{
				$this->error("获取信息失败！！！");
			}
		}else{
			$this->error("请选择要查看的订单");
		}
	}

	/**
	 * 一个订单中的一个产品跟踪信息
	 */
	public function followOneProduce(){
		$od_id=I("get.od_id");
		if(!empty($od_id)){
			$result=$this->produceapi->getOneFollowProduce($od_id);	
			if($result && is_array($result)){
				$this->assign("result",$result);
				$this->display();
			}else{
				$this->error("获取信息失败！！！");
			}
		}else{
			$this->error("选择要查看跟踪的产品!!!");
		}
	
	}


	/**
	 * 工艺单填鞋界面
	 */
	public function processOrdersDetailUi(){
		$od_id=I("get.od_id");
		if(!empty($od_id)){
			$models=M();
			$ordersdetail=$models->table("ordersdetail")->where("od_id = %d",$od_id)->find();
			$ordersdetail['od_attribute']=unserialize($ordersdetail['od_attribute']);

			$orders=$models->table("orders")->where("o_id = %d",$ordersdetail['o_id'])->find();

			$sample=$models->table("sample")->where("s_id = %d",$ordersdetail['s_id'])->find();

			$img=$models->table("image")->where("s_id = %d",$sample['s_id'])->find();

			if($sample['s_isproduce']==1){
				$ordersdetail['od_attribute']=unserialize($sample['s_attribute']);
			}

			$this->assign("ordersdetail",$ordersdetail);
			$this->assign("orders",$orders);
			$this->assign("sample",$sample);
			$this->assign("img",$img);
			$this->display();

		}else{
			$this->error("请选择要查看工艺单的鞋样！！！");
		}

	}

	/**
	 * 保存工艺单
	 */
	public function saveProcessOrdersDetail(){

		$data=I("post.");
		$img_info=$this->upload_img();
		if(!$img_info && !is_array($img_info) && count($img_info)<=0 ){
			//文件上传不成功
			$data['image']['mianliao']=$img_info['image1']['savepath'].$img_info['image1']['savename'];
			$data['image']['neiliao']=$img_info['image2']['savepath'].$img_info['image2']['savename'];

		}   
		if(!empty($data['o_id']) && !empty($data['s_id']) && !empty($data['od_id']) ){
			$map['od_id']=array("EQ",$data['od_id']);
			$ordersdetail=$this->produceapi->getOneOrdersDetail($map);
			if($ordersdetail){
				$flag=$this->produceapi->saveProcess($data);
				if($flag){
					$this->success("保存成功！！！");
				}else{
					$this->error("保存失败！！！");
				}

			}else{
				$this->error("该样品鞋不存在 ！！！");
			}
		}else{
			$this->error("请填写工艺单相关信息！！！");
		}
	}

	/**
	 * 完成订单
	 */
	public function ordersIsDone(){
		$o_id=I("post.o_id");
		if(!empty($o_id)){
			$flag=$this->produceapi->doneOrders($o_id);
			if($flag){
				$this->success("生产完结，请及时发货！！！");
			}else{
				$this->error("订单完成失败！！请确定该订单下的产品是否都生产结束！！！");
			}
		
		}else{
			$this->error("请选择要完成的订单");
		
		}

	}






	/**
	 * 修改生产跟踪单相关信息界面
	 */
	public function updateOneProduceUi(){
		$od_id=I("get.od_id");
		if(empty($od_id)){
			$this->error("请选择要跟进的产品");
		}else{
			$result=$this->produceapi->getOneFollowProduce($od_id);
			$orderstatus=$this->ordersapi->getOrdersStatus();
			if($result && is_array($result)){
				$this->assign("result",$result);
				$this->assign("orderstatus",$orderstatus);
				$this->display();	
			}else{
				$this->error("获取信息失败！！！");
			}
		}
	}


	/**
	 * 修改生产跟踪单相关信息
	 */
	public function updateOneProduce(){
		$result['fp_id']=I("post.fp_id");
		$result['fp_status']=I("post.fp_status");
		$result['fp_progress']=I("post.progress");
		$flag=true;
		foreach($result as $one ){
			if(empty($one)){
				$flag=false;
				break;
			}
		}
		$result['fp_finishnum']=I("post.finishnumber");
		if($flag){
			$flag=$this->produceapi->updateFollowProduce($result);	
			if($flag){
				$this->success("修改成功！！！");
			}else{
				$this->error("修改失败！！！");
			}
		}else{
			$this->error("请填写相关信息");
		}

	}

	/**
	 * 发货界面
	 */
	public function deliberGoodsUi(){
		$id=I("get.id");
		if(!empty($id)){
			$orders['o_id']=$id;
			$result=$this->produceapi->getFollowProduce($orders);
			if($result && is_array($result)){
				$this->assign("ordersdetail",$result['ordersdetail']);
				$this->assign("orders",$result['orders']);
				$this->display();
			}else{
				$this->error("获取信息失败！！！");
			}

		}else{
			$this->error("请选择要发货的单号");
		}
	}

	/**
	 * 发货操作
	 */
	public function deliberGoods(){
		$o_id=I("post.o_id");
		$data['sendperson']=I("post.sendperson");
		if(empty($o_id)){
			$this->error("选择发货的单号错误！！！");
		}else{
			$data['o_id']=$o_id;	
			$result=$this->produceapi->sendGoods($data);
			if($result){
				$this->success("发货成功！！！");
			}else{
				$this->error("发货失败！！！");
			}
		}
	}




	/**
	 * 产看生产跟踪单子所需要的材料
	 */
	public function getProduceMaterial(){
		$id=I("get.id");
		if(!empty($id)){
			$result=$this->produceapi->getOrdersMaterial($id);
			if($result && is_array($result)){	
				$file_name=$this->produceapi->createBurdenDocx($result);	
				if($file_name && is_string($file_name)){
					$this->assign("file_name",$file_name);
					$this->assign("material",$result);
					$this->display(); 
				}else{
					$this->error("获取订单配料表失败！！！");
				}
			}else{
				$this->error("获取订单配料表失败！！！");
			}
		}else{
			$this->error('请选择你要查看的跟踪单子！！！');
		}
	}

	/**
	 * 下载配料表
	 */
	public function downloadBurden(){

		$file_name=I("get.filename");
		if($file_name && is_string($file_name)){
			$http=new \Org\Net\Http();
			$file_path=C("DOCUMENT_SAVE_PATH").$file_name;
			$http->download($file_path,"配料表");
		}else{
			$this->error("没有该文件");
		}
	}


	/**
	 * 获取外包记录
	 */
	public function getEpibolyList(){
		$type=I("get.type");
		if(empty($type)){
			$type="all";
		}
		$map=array();
		switch ($type){
		case "all":
			$kindepiboly="所有外包记录";
			break;
		case "yes":
			$kindepiboly="完成的外包记录";
			$map['e_iscallback']=1;
			break;
		case "no":
			$kindepiboly="未完成的外包记录";
			$map['e_iscallback']=0;
			break;
		default :
			break;
		}
		$result=$this->produceapi->getEpibolyList($map);
		if($result['datalist'] && is_array($result['datalist'])){
			$this->assign("page",$result['page']);
			$this->assign("datalist",$result['datalist']);
			$this->assign("kindepiboly",$kindepiboly);
			$this->assign("types",$type);
			$this->display();
		}else{
			$this->error("还没有外包记录");
		}

	}

	/**
	 * 外包详情
	 */
	public function detailEpiboly(){
		$id=I("get.id");
		if(!empty($id)){
			$data['e_id']=intval($id);
			$result=$this->produceapi->getOneEpiboly($data);
			if($result && is_array($result)){
				$this->assign("epiboly",$result);
				$this->display();
			}else{
				$this->error("获取数据失败！！");
			}
		}else{
			$this->error("请选择要查看的外包信息");
		}	
	}

	/**
	 * 修改外包记录界面
	 */
	public function updateEpibolyUi(){
		$id=I("get.id");
		if(!empty($id)){
			$data['e_id']=intval($id);
			$result=$this->produceapi->getOneEpiboly($data);
			if($result && is_array($result)){
				$this->assign("epiboly",$result);
				$this->display();
			}else{
				$this->error("获取数据失败！！");
			}
		}else{
			$this->error("请选择要修改的外包信息");
		}	
	}

	/**
	 * 修改外包记录
	 */
	public function updateEpiboly(){

		$data=I("post.");
		unset($data['e_iscallback']);
		$flag=true;
		foreach($data as $one ){
			if(empty($one)){
				$flag=false;
				break;
			}
		}
		$data['e_iscallback']=I("post.e_iscallback");
		if($flag){
			if($data['e_iscallback']==1){
				$data['e_gettime']=date("Y-m-d :H:i:s");
			}
			$id=$data['e_id'];
			unset($data['e_id']);
			$flag=$this->produceapi->updateEpiboly($data,$id);
			if($flag){
				$this->success("修改成功！！！");
			}else{
				$this->error("修改失败！！");
			}


		}else{
			$this->error("请填写外包相关信息！！！");
		}
	}

	/**
	 * 添加外包记录界面
	 */
	public function addEpibolyUi(){
		$this->display();
	}

	/**
	 * 添加外包记录
	 */
	public function addEpiboly(){
		$data=I("post.");
		$flag=true;
		foreach($data as $one ){
			if(empty($one)){
				$flag=false;
				break;
			}
		}
		if($flag){
			$id=$this->produceapi->addEpiboly($data);
			if($id){
				$this->redirect("/Produce/detailEpiboly/",array('id'=>intval($id)));
			}else{
				$this->error("添加失败");
			}
		}else{
			$this->error("请填写相关信息");
		}
	}


	/**
	 * 搜索生产管理
	 */
	public function searchProduce(){
		$search_type=I("post.search_type");
		$search_str=I("post.search_str");
		if(!empty($search_str) && !empty($search_type)){
			$map[$search_type]=array("LIKE","%".$search_str."%");
			$result=$produceapi->getFollowProduceList($map);
			if($result){
				$this->assign('datalist',$result['datalist']);
				$this->assign('page',$result['page']);
				$this->assign('search',"搜索".$search_type."类型下的".$search_str."结果如下：");
				$this->display();
			}else{
				$this->error("搜索的内容不存在！！！");
			}
		}else{
			$this->error("请输入要搜索的内容！！！");
		}



	}





	/**
	 * 搜索外包记录
	 */
	public function searchEpiboly(){
		$search_type=I("post.search_type");
		$search_str=I("post.search_str");
		if(!empty($search_str) && !empty($search_type)){
			if($search_type=="o_displayid"){
				$map['o_displayid']=array("LIKE","%".$search_type."%");
				$models=M();
				$orders=$models->table("orders")->where($map)->getField("o_id",true);
				$tempmap['o_id']=array("IN",$orders);
				$result=$this->produceapi->getEpibolyList($tempmap);
			}else{
				$map[$search_type]=array("LIKE","%".$search_str."%");
				$result=$this->produceapi->getEpibolyList($map);

			}
			if($result){
				$this->assign('datalist',$result['datalist']);
				$this->assign('page',$result['page']);
				$this->assign('search',"搜索".$search_type."类型下的".$search_str."结果如下：");
				$this->display();
			}else{
				$this->error("搜索的内容不存在！！！");
			}
		}else{
			$this->error("请输入要搜索的内容！！！");
		}



	}


	/**
	 * 下载单个订单的跟踪单
	 */
	public function excelProduceOne(){
		if(IS_AJAX){
			$data['o_id']=I("post.o_id");
			if(!empty($data['o_id']) ){
				$result=$this->produceapi->createExcelProduce($data);
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



	/**
	 * 生成跟踪单excel文件
	 */
	public function excelProduces(){
		if(IS_AJAX){
			$data['status']=I("post.select_status");
			$data['time']=I("post.select_time");
			if(!empty($data['status']) && !empty($data['time'])){
				$result=$this->produceapi->createExcelProduce($data);
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


	/**
	 * 生成工艺单 excel 文件
	 */
	public function excelProcess(){
		if(IS_AJAX){
			$od_id=I("od_id");
			if(!empty($od_id)){
				$result=$this->produceapi->ececlOneProcess($od_id);
				if($result && is_string($result)){
					$data['flag']=true;
					$data['message']=$result;
					$this->ajaxReturn($data);
				}else{
					$data['flag']=false;
					$data['message']="获取文件失败！！！";
					$this->ajaxReturn($data);
				}
			
			}else{
				$data['flag']=false;
				$data['message']="请选择要下载的鞋样工艺单！！！";
				$this->ajaxReturn($data);
			}
		}else{
			exit();
		}
	
	}

	public function excelEpiboly(){
		if(IS_AJAX){
			$data['time']=I("post.select_time");
			$data['type']=I("post.select_type");
			if(!empty($data['type']) && !empty($data['time'])){
				$result=$this->produceapi->createExcelEpiboly($data);
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
				$data['message']="请选择要下载的外包！！！";
				$this->ajaxReturn($data);
			}
		}else{
			exit();
		}
	
	}






}
