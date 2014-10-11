<?php
/**
 * 员工管理控制器
 * 处理基本的员工操作
 */


namespace Admin\Controller;
use Think\Controller;
use User\Api\UserApi;

class UserController extends AdminController{

	private $userapi=null;
	public function _initialize(){
		parent::_initialize();
		$this->userapi=new UserApi();
	}
	public function index(){

	}
	//获取所有员工
	public function userList(){
		$result=$this->userapi->getUserList();
		if(empty($result)){
			$this->error("没有相关记录！");
		}else{
			$this->assign('show',$result['page']);
			$this->assign('list',$result['datalist']);
			$this->display('userlist');
		}

	}
	//获取某个员工详细信息
	public function userDetail(){
		$uid=I('get.uid');
		if(empty($uid)){
			$this->error("没有相关记录");
		}else{
			$result=$this->userapi->getUserDetail($uid);
			$this->assign('result',$result);
			$this->display('userdetail');
		}

	}
	//修改员工信息，信息显示在员工页面
	public function userEdit(){
		$uid=I('get.uid');
		if(empty($uid)){
			$this->error("没有相关记录！");
		}else{
			$result=$this->userapi->getUserEdit($uid);
			$this->assign('result',$result);
			$this->display('useredit');
		}
	}


	//修改员工信息
	public function userModify(){
		$data=I('post.');
		if(empty($data['m_id'])||empty($data['m_name'])||empty($data['m_price'])||empty($data['m_phone'])){
			$this->error("填写完整信息！");
		}else{
			$result=$this->userapi->userModify($data);
			if($result==true){
				$this->success("修改成功！");
			}else{
				$this->error("修改失败!请检查相关原因！");
			}
		}
	}
	//删除员工信息
	public function userDelete(){
		$m_id=I('get.uid');
		if(empty($m_id)){
			$this->error("没有相关记录！");
		}else{
			$result=$this->userapi->userDelete($m_id);
			if($result==true){
				$this->success("删除成功");
			}else{
				$this->error("删除失败!请检查相关原因！!");
			}
		}
	}
	//添加员工信息
	//获取员工类型
	public function userType(){
		$result=$this->userapi->getUserType();
		$this->assign("list",$result);
		$this->display('useradd');
	}

	//删除工种类型
	public function deleteUserKind(){
		$uid=I('get.uid');
		if (empty($uid)) {
			$this->error("删除失败!");
		}else{
			$result=$this->userapi->deleteUserType($uid);
			if($result==true){
				$this->success("删除成功!");
			}else{
				$this->error("删除成功!删除失败");
			}
		}
	}
	//将要修改的员工单价添加到页面中
	public function modifyUserKind(){
		$uid=I('get.uid');
		if(empty($uid)){
			$this->error("查询失败!");
		}else{
			$result=$this->userapi->modifyUserKind($uid);
			$this->assign('result',$result);
			$this->display("modifyuserkind");
		}
	}
	//修改员工的单价
	public function userModifyKind(){
		$data=I('post.');
		if(empty($data['mk_price'])){
			$this->error("员工单价不能为空!");
		}else{
			if($data['mk_price']>0){
				$result=$this->userapi->userModifyKind($data);
				if($result==true){
					$this->success("修改成功");
				}else{
					$this->error("修改失败");
				}
			}else{
				$this->error("员工单价不能为负！");
			}
		}
	}



	//添加工种类型
	public function addUserKind(){
		$data=I('post.');
		if(empty($data['mk_name'])||empty($data['mk_price'])){
			$this->error("工种名称或工种单价不能为空");
		}else{
			$result=$this->userapi->addUserKindd($data);
			if($result==1){
				$this->error("该类型已有！");
			}
			if($result==2){
				$this->success("添加成功");
			}
			if($result==3){
				$this->error("添加失败");
			}
		}
	}


	//添加一个员工信息，获取员工信息
	public function addUserDetail(){
		$casetype=$this->userapi->getUserTypeBy();
		$this->assign('casetype',$casetype);
		if(is_array($casetype)){
			$this->display('adduserdetail');
		}else{
			$this->error("请先添加员工类型！！！");
		}
	}
	//添加一个员工信息
	public function addUserSingle(){
		$data=I('post.');
		if(empty($data['m_name'])||empty($data['m_price'])||empty($data['m_phone'])){
			$this->error("员工姓名或员工薪酬不能为空或号码不能为空！");
		}else{
			$result=$this->userapi->addUserSingle($data);
			if($result){
				$this->success("添加成功");
			}else{
				$this->error("添加失败！");
			}
		}
	}
	//查询某个用户信息
	public function findUser(){
		$data=I('post.m_name');
		if(empty($data)){
			$this->error("请输入用户名");
		}else{
			$result = $this->userapi->findOneUser($data);
			if(empty($result)){
				$this->error("没有相关记录");
			}else{
				$this->assign('list',$result);
				$this->display('userlist');
			}
		}
	}

	//检查工种名称
	public function checkUserKind(){
		if(IS_AJAX){
			$mk_name=I('post.mkname');
			if(empty($mk_name)){
				$data['flag']=false;
				$data['message']="工种类型名称不能为空!!!";
				$this->ajaxReturn($data);
			}else{
				$result=$this->userapi->checkUserKind($mk_name);
				if($result){
					$data['flag']=ture;
					$data['message']="该工种已存在!";
					$this->ajaxReturn($data);
				}else{
					$data['flag']=true;
					$this->ajaxReturn($data);
				}
			}
		}else{
			exit();
		}
	}

	/**
	 * 搜索模个工种下的员工
	 */
	public function searchUser(){
		$search_type=I("post.search_type");
		$search_str=I("post.search_str");
		if(!empty($search_str) && !empty($search_type)){
			$map[$search_type]=array("LIKE",$search_str);
			$result=$this->userapi->searchUserList($map);
			if($result){
				$this->assign('list',$result['datalist']);
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
















}
