<?php
/**
 *处理登录请求
 *引导进入后台管理界面
 *
 */

namespace Admin\Controller;
use Think\Controller;
use User\Api\UserApi;

class IndexController extends Controller{

	private $userapi=null;

	public function _initialize(){
		$this->userapi=new UserApi();
	}


	//login 登录
	public function index(){
		$this->display("index");
	}



	//ajax 请求验证是否存在用户名
	public function check_account(){
		if(IS_AJAX){
			$account=I("post.account");
			if(empty($account)){
				$data['flag']=false;
				$data['message']="请填写账号";
				$this->ajaxReturn($data);
			}else{
				$result=$this->userapi->checkAccount($account);
				if($result){
					$data['flag']=true;
					$this->ajaxReturn($data);
				}else{
					$data['flag']=true;
					$data['message']="账号不存在";
					$this->ajaxReturn($data);
				}   
			}
		}else{
			exit();
		}
	}


	//ajax 请求   验证 验证码是否正确
	public function check_verify(){
		if(IS_AJAX){
			$verifystring=I("post.verify_code");
			if(empty($verifystring)){
				$data['flag']=true;
				$data['message']="请填写验证码";
				$this->ajaxReturn($data);
			}else{
				$flag=$this->check_verify_code($verifystring);
				if($flag){
					$data['flag']=true;
					$this->ajaxReturn($data);
				}else{
					$data['flag']=true;
					$data['message']="验证码错误！";
					$this->ajaxReturn($data);
				}   
			}
		}else{
			exit();
		}
	}


	//验证登录
	public function loging(){
		if(IS_AJAX){
			$useraccount=I("post.useraccount");
			$userpassword=I("post.password");
			$verify_code=I("post.verify_text");
			if(empty($useraccount) || empty($userpassword) || empty($verify_code)){
				$data['flag']=true;
				$data['message']="请填完整信息！！！";
				$this->ajaxReturn($data);
			}else{
				$result=$this->userapi->login($userpassword,$useraccount);
				if($result==TRUE){
					//返回信息
					$data1['flag']=true;
					$data1['url']=U('Admin/Index/main');
					$data1['message']="登录成功";
					$this->ajaxReturn($data1);
				}else{
					$data['flag']=true;
					$data['message']="密码或账号错误！！！";
					$this->ajaxReturn($data);
				}
			}
		}else{
			exit();
		}
	}




	//主界面
	public function main(){
		if(session('?LOGIN_INFO')){
			$this->display();
		}else{
			$this->index();
		}

	}


	//生成验证码
	public function create_verify(){
		$Verify = new \Think\Verify();
		$Verify->length=4;
		$Verify->expire=60;
		$Verify->useImgBg = true;
		$Verify->useNoise = false;
		$Verify->entry();
	}

	private function check_verify_code($code, $id = ''){
		$verify = new \Think\Verify();
		return $verify->check($code, $id);
	}




	//退出系统
	public function exitsystem(){
		session(null);
		$this->redirect('index');
	}

	//跳到管理首页
	public function home(){
		$this->redirect('main');
	}

}







?>
