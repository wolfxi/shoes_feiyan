<?php
namespace Admin\Controller;
use Think\Controller;


class AdminController extends Controller{


	public function _initialize(){
		if((is_login())){
			$this->assign('login_info',session('LOGIN_INFO'));
			$this->assign('upload_img',C('UPLOADIMG_URL'));

		}else{
			//跳到登录界面：TODO：：
			$this->redirect('Index/index');
			exit();	
		}

		/**对输入的变量进行过滤**/
		$_POST=self::filterInput($_POST);
	}




	//对输入的内容进行处理
	//@param $input 可以是数组或变量
	protected function filterInput($input){
		if(is_array($input) && count($input)>0){
			foreach($input as $key => $one){
				$input[$key]=self::fileterVar($one);

			}

		}else{

			$input=self::fileterVar($input);
		}
		return $input;

	}

	//对单个变量进行过滤
	//@param $input_var  单个变量
	//return 过滤后的字符串
	protected function fileterVar($input_var){

		if(is_numeric($input_var)){
			return $input_var;
		}else{
			$input_var=safe_replace($input_var);
			$input_var=remove_xss($input_var);
			$input_var=preg_replace('/<script.*>.*(\r)*(\n)*(\a)*(\s)*(<\/script>)?/','',$input_var);
		}
		return $input_var;
	}



	/**返回上传图片的信息
	 * 返回的是一个关联数组
	 * 包含了文件名和文件的路劲
	 */
	public function upload_img(){
		$file=$_FILES;
		if($file && count($file)>0){
			$upload = new \Think\Upload();// 实例化上传类
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->savePath  =     ""; // 设置附件上传目录
			$upload->rootPath  = 	 C('UPLOADIMG_DIR');//设置附件上传根目录
			// 上传文件 
			$result   =   $upload->upload();
			if(!$result) {// 上传错误提示错误信息
				return false;
			}else{// 上传成功 获取上传文件信息
				return $result;
			}
		}else{
			$file=array();
			return $file;
		}

	}

	/**
	 * 下载文件
	 * @param $file_name  文件名
	 * return void
	 */
	public function downloadFile(){
		$file_name=I("get.filename");
		if($file_name && is_string($file_name)){
			$http=new \Org\Net\Http();
			$file_path=C("DOCUMENT_SAVE_PATH").$file_name;
			$http->download($file_path,"订单");
		}else{
			$this->error("没有该文件");
		}


	}



}
