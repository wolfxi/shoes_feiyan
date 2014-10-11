<?php
/**====================================管理员控制器============================**/
//用于管理员自身的一些操作
//不涉及到项目本身的业务逻辑

namespace Admin\Controller;
use Think\Controller;
use Admininfo\Api\AdmininfoApi;

class AdmininfoController extends AdminController{

	private $admininfoapi=null;
	public function _initialize(){

		parent::_initialize();
		$this->admininfoapi=new AdmininfoApi();

	}
	//left 边栏
	public function left(){
		$this->display();
	}
	//top 边栏
	public function top(){
		$this->display();
	}
	//管理员信息
	public function index(){
		session_start();
		$account=$_SESSION['account'];
		$result=$this->admininfoapi->getAdmininfo($account);
		$this->assign("result",$result);
		$this->display("index");
	}

	//修改密码
	public function passwordUi(){
		session_start();
		$account=$_SESSION['account'];
		$result=$this->admininfoapi->getAdmininfo($account);
		$this->assign("result",$result);
		$this->display("passwordUi");
	}

	//修改密码
	public function updatePassword(){
		$m_id=I('post.m_id');
		$oldpass=I('post.oldpass');
		$newpass=I('post.m_passwords');
		$renewpass=I('post.newpassword2');
		if(empty($oldpass)||empty($newpass)||empty($renewpass)){
			$this->error("填写详细信息");
		}else{
			$result=$this->admininfoapi->updatePasswordInfoConform($m_id,$oldpass,$newpass,$renewpass);
			if($result==1){
				$this->error("输入密码与原密码不一致");
			}
			if($result==2){
				$this->error("两次输入密码不一致");
			}
			if($result==3){
				$this->success("修改成功");
			}
			if ($result==4) {
				$this->error("修改失败");
			}
		}
	}
	//修改个人信息 操作
	public function updateBaseInfoBy(){
		$data=I('post.');
		$result=$this->admininfoapi->updateAdminInfo($data);
		if($result){
			$this->success("修改成功!");
		}else{
			$this->error("修改失败!");
		}
	}


	//修改个人信息 UI
	public function updateBaseInfoUi(){
		session_start();
		$account=$_SESSION['account'];
		$result=$this->admininfoapi->getAdmininfo($account);
		$this->assign("result",$result);
		$this->display("adminmodify");
	}

	//获取管理员列表
	public function adminList(){
		$result=$this->admininfoapi->getAdminList();
		$this->assign('list',$result);
		$this->display();
	}


	//修改管理员组信息
	public function updateAdminInfoUi(){
		$m_id=I('get.m_id');
		$result=$this->admininfoapi->updateAdminInfoUi($m_id);
		if(empty($result)){
			$this->error("你没有权限");
		}else{
			$this->assign('result',$result);
			$this->display('updateAdminInfoUi');
		}
	}

	//修改管理员信息
	public function updateAdminGroupInfo(){
		$data=I('post.');
		if(empty($data['m_name'])||empty($data['m_phone'])||empty($data['m_idcard'])||empty($data['m_address'])){
			$this->error("请填写完整信息 ");
		}else{
			$result=$this->admininfoapi->updateAdminGroupInf($data);
			if($result){
				$this->success("修改成功");
			}else{
				$this->error("修改失败");
			}
		}
	}
	//添加管理员类型
	public function addoneadminkindUi(){
		$result=$this->admininfoapi->getMemberKind();
		$this->assign('list',$result);
		$this->display();
	}

	//获取管理员类型
	public function getMemberKindByAdd(){
		$result=$this->admininfoapi->getMemberKind();
		$this->assign('casetype',$result);
		if(is_array($result)){
           $this->display('addoneadminUi');
        }else{
            $this->error("请先添加管理员类型！！！");
        }
	}

	//添加管理员
	public function addOneAdmin(){
		$data=I('post.');
		if(empty($data['m_account'])||empty($data['m_passwords'])){
			$this->error("账号和密码不能空");
		}else{
			$result=$this->admininfoapi->addOneAdminApi($data);
			if($result==1){
				$this->error("该账号已存在");
			}
			if($result==2){
				$this->success("添加成功");
			}
			if($result==3){
				$this->error("添加失败");
			}
		}
	}

	//删除管理员类型
	public function deleteMemberKind(){
		$m_id=I('get.mk_id');
		$result=$this->admininfoapi->deleteMemberKind($m_id);
		if($result==1){
			$this->error("没有权限");
		}
		if ($result==2) {
			$this->success("删除成功");
		}
		if($result==3){
			$this->error("删除失败");
		}
	}

	//添加管理员类型
	public function addMemberkindType(){
		$data=I('post.');
		if(empty($data['mk_name'])){
			$this->error("请输入管理员类型");
		}else{
			$result=$this->admininfoapi->addMemberkindType($data);
			if($result==1){
				$this->error("没有权限");
			}
			if ($result==2) {
				$this->success("添加成功");
			}
			if($result==3){
				$this->error("添加失败");
			}
		}
	}

		//检验管理员账号
	public function checkAdminAccount(){
		if (IS_AJAX) {
			$m_account=I('post.account');
			if(empty($m_account)){
				$data['flag']=false;
				$data['message']="请填写账号";
				$this->ajaxReturn($data);
			}else{
				$result=$this->admininfoapi->checkAdminAccount($m_account);
				if($result){
					$data['flag']=true;
					$data['message']="该账号已存在";
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

	//检验管理员类型
	public function checkMemberKind(){
		if(IS_AJAX){
			$mk_name=I('post.mkname');
			if(empty($mk_name)){
				$data['flage']=false;
				$data['message']="管理员类型名称不能为空";
				$this->ajaxReturn($data);
			}else{
				$result=$this->admininfoapi->checkMemberKind($mk_name);
				if($result){
					$data['flag']=true;
					$data['message']="该类型已有";
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









	//判断是否有权限来操作其他管理员
	public function checkHasPower(){
		//TODO:根据数据库设计表的字段来判断
	}

	//其他管理员列表
	public function otherAdminList(){
		$this->display();
	}

	//删除其他管理员
	public function deleteOneAdmin(){
	
		$this->display();
	}


	//查看其他管理员信息
	public function oneAdminInfoUi(){
		
		$this->display();

	}

	//修改其他管理员权限
	public function updateOneAdminPower(){
		$this->display();
	
	}





















}
?>
