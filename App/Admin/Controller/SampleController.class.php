<?php

/**
 * 样品控制器  管理样品
 */

namespace Admin\Controller;
use Sample\Api\SampleApi;

class SampleController extends AdminController{

	private $sampleapi=null;


	public function _initialize(){
		parent::_initialize();
		$this->sampleapi=new SampleApi();

	}


	//显示样品列表
	public function index(){

		$result=$this->sampleapi->getSampleList();
		if($result && is_array($result)){
			$this->assign('datalist',$result['datalist']);
			$this->assign('page',$result['page']);
			$this->display();
		}else{
			$this->error('还没有样品，请添加样品');
		}
	}



	//修改样品信息界面
	public function updateSampleUi(){
		$id=I('get.id');
		if(!intval($id) && empty($id)){
			$this->error('请选择要修改的样品信息');
			return ;
		}

		$result=$this->sampleapi->getOneSample($id);
		if($result && is_array($result)){
			$result['s_attribute']=unserialize($result['s_attribute']);
			$this->assign('sample',$result);
			$this->display();
		}else{
			$this->error('要修改的样品不存在！！！');
			return ;
		
		}
	}



	//修改样品信息
	public function updateSample(){
		$data=I('post.');
		$flag=$this->sampleapi->updateSample($data);
		if($flag){
			$this->redirect('/Sample/detail/',array('id'=>$data['id']));
		}else{
			$this->error("修改失败！！！");
		}
	}



	//删除样品
	public function	deleteSample(){
		$id=I('get.id');
		if(!empty($id) && intval($id)){
			$flag=$this->sampleapi->statusSample($id);
			if($flag){
				$this->success('下架成功');
			
			}else{
				$this->error('下架失败');
			}

		
		}else{
			$this->error('请选择你要下架的样品');
		}

	}





	//添加样品界面
	public function addSampleUi(){
		$this->display();
	}


	//添加样品
	public function addSample(){
		$img_info=$this->upload_img();
		if(!$img_info && !is_array($img_info) && count($img_info)<=0 ){
			//文件上传不成功
			$this->error('文件上传不成功！！！');	
			return ;
		}	
		$data=I('post.');
		//数据检测是否合理
		if(empty($data['name']) || empty($data['models']) || empty($data['sizes']) || empty($data['price']) || empty($data['sole']['models']) || empty($data['sole']['wight']) || empty($data['insole']['models']) || empty($data['insole']['firm']) || empty($data['innerbox']['models']) || empty($data['innerbox']['format']) || empty($data['outerbox']['models']) || empty($data['outerbox']['format'])){
			$this->error('请填写样品重要信息！！！');
			return ;	
		}else{
			$data['img_info']=$img_info;
			$result=$this->sampleapi->addSample($data);
			if($result && is_int($result)){
				$this->redirect("/Sample/detail/",array('id'=>$result));
			}else{
				$this->error('样品添加失败！！！');	
			}
		}
	}







	//显示单个样品的信息
	public function detail($s_id=null){

		$s_id=empty($s_id) ? I('get.id') : $s_id;
		if(empty($s_id)){
			$this->error('请选择要查看的样品信息');
			return ;
		}

		$result=$this->sampleapi->getOneSample($s_id);
		if($result && is_array($result)){
			$result['s_attribute']=unserialize($result['s_attribute']);
			$this->assign('sample',$result);
			$this->display();

		}else{
			$this->error('选择要查看的样品不存在');
			return ;

		}
	}



	public function soldoutList(){
		$result=$this->sampleapi->getSampleList("1");
		if($result && is_array($result) && count($result)>0){
			$this->assign('datalist',$result['datalist']);
			$this->assign('page',$result['page']);
			$this->display();
		}else{
			$this->error('还没有下架的样品');
		
		}
	}


	public function recoverSample(){
		$id=I("get.id");
		if(empty($id) && is_int($id)){
			$flag=$this->sampleapi->statusSample($id,0);
			if($flag){
				$this->redirect("/Sample/detail/",array('id'=>intval($id)));
			}else{
				$this->error("上架失败");
			}
		
		}else{
			$this->error("请选择要上架的样品信息");
		}
	}


	/**
	 * 根据样品型号/样品选型查找样品
	 */
	public function searchSample(){
		$search_type=I("post.search_type");
		$search_str=I("post.seatch_str");
		if(!empty($search_str) && !empty($search_type)){
			$data[$search_type]=array("LIKE",$search_str);	
			$result=$this->sampleapi->searchSampleList($data);
			if($result && is_array($result)){
				$this->assign("search","类型为".$search_type.",搜索".$search_str."的结果如下")；
				$this->assign("datalist",$result['datalist']);
				$this->assign("page",$result['page']);
				$this->display();
			}else{
				$this->error("搜索的内容不存在！！！");
			}
		}else{
			$this->error("请填写要搜索的内容");
		}
	}






















}
