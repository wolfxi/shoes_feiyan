<?php
/**
 * 员工管理控制器
 * 处理基本的员工操作
 */


namespace Admin\Controller;
use Think\Controller;
use Customer\Api\CustomerApi;

class CustomerController extends AdminController{

	private $customerapi=null;
	public function _initialize(){
		parent::_initialize();
		$this->customerapi=new CustomerApi();
	}
	public function index(){

	}

	//客户管理列表
	public function supplierListUi(){
		$result=$this->customerapi->getSupplierListUi();
		if(empty($result)){
			$this->error("没有相关记录!");
		}else{
			$this->assign('show',$result['page']);
			$this->assign('list',$result['datalist']);
			$this->display('supplierlistUi');
		}
	}
	//删除某个客户
	public function supplierDelete(){
		$f_id=I('get.uid');
		if(empty($f_id)){
			$this->error("操作错误!");
		}else{
			$result=$this->customerapi->deleteSupplierSingle($f_id);
			if($result){
				$this->success("删除成功!");
			}else{
				$this->error("删除失败!");
			}
		}
	}
	//某个客户详情
	public function supplierUpdateUi(){
		$f_id=I('get.uid');
		if(empty($f_id)){
			$this->error("操作错误!");
		}else{
			$result=$this->customerapi->getSupplierUpdateUi($f_id);
			if(empty($result)){
				$this->error("没有相关记录!");
			}else{
				$this->assign('result',$result);
				$this->display();
			}
		}
	}
	//修改客户信息
	public function supplierEdit(){
		$data=I('post.');
		if(empty($data['f_name'])||empty($data['f_phone'])||empty($data['f_address'])||empty($data['f_kind'])){
			$this->error("请填写完整信息");
		}else{
			$result=$this->customerapi->editSupplier($data);
			if($result==true){
				$this->success("修改成功!");
			}else{
				$this->error("修改失败!");
			}
		}
	}
	//增加一个客户
	public function addSupplierSingle(){
		$data=I('post.');
		if(empty($data['f_name'])||empty($data['f_phone'])||empty($data['f_address'])||empty($data['f_kind'])){
			$this->error("请填写完整信息!");
		}else{
			$result=$this->customerapi->addFirmSingle($data);
			if($result){
				$this->success("添加成功" );
			}else{
				$this->error("添加失败!");
			}
		}
	}

	//查询某个客户信息
	public function findFirm(){
		$data=I('post.');
		if(empty($data['f_name'])){
			$this->error("请输入客户名");
		}else{
		    $result =$this->customerapi->findFirmOne($data);
		    if(empty($result)){
		    	$this->error("没有相关记录");
		    }else{
		   	 	$this->assign('list',$result);
				$this->display('supplierlistUi');
		    }
		}


	}


	/**
	 * 搜索客户信息
	 */
	public function searchFirm(){
		$search_type=I("post.search_type");
		$search_str=I("post.search_str");
		if(!empty($search_str) && !empty($search_type)){
			$map[$search_type]=array("LIKE",$search_str);
			$result=$this->customerapi->searchFirm($data);
			if($result){
				$this->assign("list",$result['datalist']);
				$this->assign("page",$result['page']);
				$this->assign("search","搜索".$search_type."类型下的".$search_str."结果如下：");
				$this->display();
			}else{
				$this->error("搜索的数据不存在！！！");
			}
		}else{
			$this->error("请输入要搜索的内容！！！");
		}
	
	}
















}
