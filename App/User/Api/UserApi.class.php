<?php

/**==============用户本身API中心================**/




namespace User\Api;
use User\Api\Api;


class UserApi extends Api{


	//初始化（构造函数的一部分）
	//实例化用户模型
	function _init(){
		$this->model=D('Member');	

	}
	//获取所有员工列表
	/***
	 *获取所有员工列表
	 *
	 *return array 二维数组
	 */
	public function getUserList(){
		$count = $this->model->where("m_isadmin = %d",0)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$result['page'] = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$result['datalist'] = $this->model->where("m_isadmin = %d",0)->limit($Page->firstRow.','.$Page->listRows)->select();
		return $result;
	}

	/***
	 *员工详情
	 *$uid 查询条件
	 *return array 一维数组
	 */
	public function getUserDetail($uid){
		$result=$this->model->where("m_id='%d'",$uid)->find();
		return $result;
	}

	/***
	 *员工详情修改
	 *$uid 查询条件
	 *return array 一维数组
	 */
	public function getUserEdit($uid){
		$result=$this->model->where("m_id='%d'",$uid)->find();
		return $result;
	}
	//修改员工信息
	/***
	 *修改员工信息
	 *$data 查询条件
	 *return true or false
	 */
	public function userModify($data){
		$uid=$data['m_id'];
		$result=$this->model->where("m_id='%d'",intval($uid))->data($data)->save();
		if($result){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	//删除员工信息
	/***
	 *删除员工信息
	 *$uid 查询条件
	 *return true or false
	 */
	public function userDelete($uid){
		$result=$this->model->where("m_id='%d'",$uid)->delete();
		if($result){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	//员工类型获取
	/***
	 *员工类型获取
	 *
	 *return array 二维数组
	 */
	public function getUserTypeBy(){
		$this->model=null;
		$this->model1=D("Memberkind");
		$result=$this->model1->select();
		return $result;
	}
	//增加员工
	/***
	 *增加员工
	 *$data 查询条件
	 *return true or false
	 */
	public  function addUserSingle($data){
		$uid='';
		$result=$this->model->where("m_id=%d",intval($uid))->data($data)->add();
		return $result;
	}
	/***
	 *查询某个员工信息
	 *$data 查询条件
	 *return array 二维数组
	 */
	//查询某个员工信息
	public function findOneUser($data){
		$where['m_name']=array('like',"%{$data}%");
		$result=$this->model->where($where)->select();
		return $result;
	}

	/***
	 *获取员工种类
	 *$data 查询条件
	 *return array 二维数组
	 */
	//获取员工种类
	public function getUserType(){
		$this->model=null;
		$this->model1=D('Memberkind');
		$result=$this->model1->select();
		return $result;
	}
	/***
	 *删除员工种类
	 *$uid 查询条件
	 *return true or false
	 */
	//删除员工种类
	public function deleteUserType($uid){
		$this->model=null;
		$this->model1=M("Memberkind");
		$result=$this->model1->where("mk_id='%d'",$uid)->delete();
		return $result;
	}
	/***
	 *修改工种单价，查询单价
	 *$uid 查询条件
	 *return array 一维数组
	 */
	//修改工种单价，查询单价
	public function modifyUserKind($uid){
		$this->model=null;
		$this->model1=D("Memberkind");
		$result=$this->model1->where("mk_id='%d'",$uid)->find();
		return $result; 
	}
	/***
	 *修改单价
	 *$data 查询条件
	 *return true or false
	 */
	//修改单价
	public function userModifyKind($data){
		$this->model=null;
		$this->model1=D("Memberkind");
		$mk_id=$data['mk_id'];
		$result=$this->model1->where("mk_id='%d'",intval($mk_id))->data($data)->save();
		return $result;
	}

	/***
	 *添加工种类型
	 *$data 查询条件
	 *return true or false
	 */
	public function addUserKindd($data){
		$result=0;
		$mk_id="";
		$this->model=null;
		$this->model1=D("Memberkind");
		$find=$this->model1->where("mk_name='%s'",$data['mk_name'])->find();
		if(!empty($find)){
			$result=1;
		}else{
			$result=$this->model1->where("mk_id=%d",intval($mk_id))->data($data)->add();
			if($result){
				$result=2;
			}else{
				$result=3;
			}
		}
		return $result;
	}


	//检查工种类型
	//return boolean
	//帐号存在true 不存在false
	//@param account
	public function checkUserKind($mk_name){
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





	//登录接口
	//return boolean 
	//成功：ture  失败：false
	//支持email 和帐号登录
	public function login($password,$account=null){
		$result=false;
		//帐号登录
		$result=$this->model->where("m_account='%s' AND m_passwords='%s'",$account,md5($password))->find();
		if(is_array($result) && md5($password)===$result['m_passwords']){
			$result['m_passwords']=null;
			session(null);
			session('LOGIN_INFO',$result);
			$_SESSION['account']=$account;
			return TRUE;
		}else{
			return FALSE;
		}



	} 




	//检测帐号
	//return boolean
	//帐号存在true 不存在false
	//@param account
	public function checkAccount($account){
		if(!empty($account)){
			$result=$this->model->where('m_account="%s"',$account)->find();
			if($result && is_array($result)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}

	}


	//检测邮箱是否可以注册
	//return boolean
	//邮箱存在false 不存在true
	//@param email 用户邮箱
	public function checkEmail($email){
		if(is_email($email)){
			$result=$this->model->where('u_email="%s"',$email)->find();
			if($result && is_array($result)){
				return false;
			}else{
				return true;
			}
		}else{

			return false;
		}	
		return false;
	}


	//验证验证码
	//return boolean
	//正确true 错误false
	//@param code <验证吗>
	public function checkVerify($verify){
		$verify=new \Think\Verify();
		return $verify->check($verify);

	}


	/*跟新用户信息  单个字段更新
	  @param $field :字段名
	  @param $value :值
	  return boolean true /flase
	 */
	public function updateOneFiels($field,$value){

		$valuetype="%s";
		if(is_int($value)){
			$valuetype="%d";
		}
		$flag=$this->model->where("u_id=%d",get_user_field('u_id'))->data(array($field=>$value))->save();
		if($flag){
			return true;
		}else{
			return false;
		}
	}


	/**
	 * 搜索员工信息
	 * @param $data 搜索条件
	 * return array  二维数组
	 * 			false false
	 */
	public function searchUserList($data){
		$count = $this->model->where($data,0)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$result['page'] = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$result['datalist'] = $this->model->where($data)->limit($Page->firstRow.','.$Page->listRows)->select();
		return $result;
	}










}
?>
