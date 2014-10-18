<?php

namespace Admin\Controller;
use Think\Controller;
use Produce\Api\ProduceApi;

class ProduceController extends AdminController{

	protected $produceapi=null;
	function _initialize(){

		parent::_initialize();
		$this->produceapi=new ProduceApi();
	}

	/**
	 * 现在生产跟踪着的单子
	 */
	public function index(){

		$map['fp_status']= array("NEQ", "发货完结");
		$result=$this->produceapi->getFollowProduceList($map);
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

		$map['fp_status']= array("EQ", "生产完结");
		$result=$this->produceapi->getFollowProduceList($map);
		if($result){
			$this->assign("datalist",$result['datalist']);
			$this->assign("page",$result['page']);
			$this->display();
		}else{
			$this->error("还没有要发货的单子");
		}
	}




	/**
	 * 生产过程记录详细
	 */
	public function produceDetail(){
		$id=I("get.id");
		if(!empty($id)){
			$result=$this->produceapi->getOneFollowProduce($id);
			if($result && is_array($result)){
				$this->assign("result",$result);
				$this->display();
			}else{
				$this->error("获取信息失败！！！");
			}	
		}else{
			$this->error("请选择要查看的单子");
		}
	}







	/**
	 * 修改生产跟踪单相关信息界面
	 */
	public function updateProduceUi(){
		$id=I("get.id");
		if(empty($id)){
			$this->error("请选择要修改的跟踪单");
		}else{
			$result=$this->produceapi->getOneFollowProduce($id);
			if($result && is_array($result)){
				$this->assign("result",$result);
				$this->display();	
			}else{
				$this->error("获取信息失败！！！");
			}
		}
	}


	/**
	 * 修改生产跟踪单相关信息
	 */
	public function updateProduce(){
		$result['fp_id']=I("post.fp_id");
		$result['o_id']=I("post.o_id");
		$result['fp_status']=I("post.fp_status");
		$result['fp_progress']=I("post.progress");
		$result['fp_number']=I("post.number");
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
			$result=$this->produceapi->getOneFollowProduce($id);
			if($result && is_array($result)){
				$this->assign("result",$result);
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

		$fp_id=I("post.fp_id");
		$o_id=I("post.o_id");
		$data['sendperson']=I("post.sendperson");
		if(empty($fp_id) || empty($o_id)){
			$this->error("选择发货的单号错误！！！");
		}else{
			$data['fp_id']=$fp_id;
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
			$map[$search_type]=array("LIKE",$search_str);
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
			$map[$search_type]=array("LIKE",$search_str);
			$result=$this->produceapi->getEpibolyList($map);
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
	 * 生成跟踪单excel文件
	 */
	public function excelProduce(){
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








}
