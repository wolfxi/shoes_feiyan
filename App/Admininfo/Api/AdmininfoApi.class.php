<?php

/**==============adminAPI中心================**/




namespace Admininfo\Api;
use Admininfo\Api\Api;


class AdmininfoApi extends Api{


	//初始化（构造函数的一部分）
	//实例化用户模型
	function _init(){
		$this->model=D('Member');	

	}
	/**获取管理员列表
	*return array 二维数组
	*/
	public function getAdminList(){
		$result=$this->model->where("m_isadmin=1 And mk_id not in(1)")->select();
		return $result;
	}

	/**获取管理员登录信息
	*return array 一维数组
	*/
	public function getAdmininfo($m_id){
		$result=$this->model->where("m_account='%s'",$m_id)->find();
		return $result;
	}

	/**修改管理员信息
	*return true or false
	*/
	public function updateAdminInfo($data){
		$m_id=$data['m_id'];
		$result=$this->model->where("m_id=%d",$m_id)->data($data)->save();
		return $result;
	}


	/**修改管理员密码
	*return true or false
	*/
	public function updatePasswordInfoConform($m_id,$oldpass,$newpass,$renewpass){
		$result=0;
		$nepas['m_passwords']=trim(md5($newpass));
		$res=$this->model->where("m_id=%d",intval($m_id))->find();
		if($res['m_passwords']!=md5($oldpass)){
			$result=1;
		}else{
			if($newpass!=$renewpass){
				$result=2;
			}else{
				$res2=$this->model->where("m_id=%d",intval($m_id))->data($nepas)->save();
				if($res2){
					$result=3;
				}else{
					$result=4;
				}
			}
		}
		return $result;
	}





	/**
	*修改管理员组的信息
	*$m_id 查询条件
	*return empty or !empty  if(empty no access) else have access
	*/
	public function updateAdminInfoUi($m_id){
		$result=null;
		session_start();
		$account=$_SESSION['account'];
		$res=$this->model->where("m_account='%s'",trim($account))->find();
		if($res['m_id']==$m_id || $res['mk_id']==1){
			$result=$this->model->where("m_id='%d'",intval($m_id))->find();
		}else{
			$result=null;
		}
		return $result;
	}

	/**
	*修改管理员组的信息
	*$data查询条件
	*return true or false
	*/
	public function updateAdminGroupInf($data){
		$m_id=$data['m_id'];
		$result=$this->model->where("m_id=%d",intval($m_id))->data($data)->save();
		return $result;
	}

	/**
	*获取管理员类型列表
	*
	*return array 二维数组
	*/
	public function getMemberKind(){
		$this->model=null;
		$this->model1=D("Memberkind");
		$result=$this->model1->where("mk_isadmin=1 AND mk_id not in(1) ")->select();
		return $result;
	}

	/**
	*添加管理员
	*
	*return 1 账号，2 成功，3，失败
	*/
	public function addOneAdminApi($data){
		$result=0;
		$m_id='';
		$adddata['m_account']=$data['m_account'];
		$adddata['m_name']=$data['m_name'];
		$adddata['m_passwords']=md5($data['m_passwords']);
		$adddata['m_phone']=$data['m_phone'];
		$adddata['mk_id']=$data['mk_id'];
		$adddata['m_idcard']=$data['m_idcard'];
		$adddata['m_address']=$data['m_address'];
		$adddata['m_remark']=$data['m_remark'];
		$adddata['m_isadmin']=$data['m_isadmin'];
		$res=$this->model->where("m_account='%s'",$m_account)->find();
		if(!empty($res)){
			$result=1;
		}else{
			$res2=$this->model->where("m_id=%d",$m_id)->data($adddata)->add();
			if($res2){
				$result=2;
			}else{
				$result=3;
			}
		}
		return $result;
	}


	/**
	*删除管理员类型
	*
	*return 1 没权限，2 成功，3，失败
	*/
	public function deleteMemberKind($m_id){
		$result=0;
		$login=$_SESSION['LOGIN_INFO'];
		$res=$this->model->where("m_account= '%s' ",$login['m_account'])->find();
		if($res['mk_id']==1){
			$this->model1=D("Memberkind");
			$data['mk_isdelete']=1;
			$result=$this->model1->where("mk_id='%d'",intval($m_id))->data($data)->save();
			if($result){
				$result=2;
			}else{
				$result=3;
			}
		}else{
			$result=1;
		}
		return $result;
	}


	/**
	*添加管理员类型
	*
	*return 1 没权限，2 成功，3，失败
	*/
	public function addMemberkindType($data){
		$result=0;
		$mk_id='';
		session_start();
		$account=$_SESSION['account'];
		$res=$this->model->where("m_account='%s'",$account)->find();
		if($res['mk_id']==1){
			$this->model=null;
			$this->model1=D("Memberkind");
			$data['mk_isdelete']=0;
			$data['mk_isadmin']=1;
			$result=$this->model1->data($data)->add();
			if($result){
				$result=2;
			}else{
				$result=3;
			}
		}else{
			$result=1;
		}
		return $result;
	}

	//检测管理员帐号
	//return boolean
	//帐号存在true 不存在false
	//@param account
	public function checkAdminAccount($account){
		if(!empty($account)){
			$result=$this->model->where("m_account='%s'",$account)->find();
			if($result&&is_array($result)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}

	//检测管理员帐号
	//return boolean
	//帐号存在true 不存在false
	//@param mk_name
	public function checkMemberKind($mk_name){
		if(!empty($mk_name)){
			$this->model1=D("Memberkind");
			$result=$this->model1->where("mk_name='%s'",$mk_name)->find();
			if($result&&is_array($result)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}





























}
?>
