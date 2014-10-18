<?php

/**==============用户本身API中心================**/




namespace Sample\Api;
use Sample\Api\Api;
use Sample\Model\SampleModel;

class SampleApi extends Api{


	//初始化（构造函数的一部分）
	//实例化用户模型
	public function _init(){
		$this->model= new SampleModel();	

	}


	/**添加新样品
	 * return new sample id(int)
	 * success   return id 
	 * false   return false 
	 */
	public function addSample($data){
		//整理数据
		$sample['s_name']=$data['name'];
		$sample['s_models']=$data['models'];
		$sample['s_material']=$data['material'];
		$sample['s_sizes']=$data['sizes'];
		$sample['s_price']=$data['price'];
		$sample['s_mould']=$data['mould'];
		$sample['s_soldout']= 0;
		$sample['s_isproduce']=0;
		$sample['s_neili']=$data['neili'];
		$sample['s_dadi']=$data['dadi'];
		$sample['s_chexian']=$data['chexian'];
		$sample['s_remark']=$data['remark'];


		//开启数据库事务
		$models=M();
		$models->startTrans();
		$result=$models->table('sample')->data($sample)->add();
		if($result && is_int($result)){
			$img=array();
			foreach($data['img_info'] as $img_one){
				$temp['i_url']=$img_one['savepath'].$img_one['savename'];
				$temp['s_id']=$result;
				array_push($img,$temp);
				unset($temp);
			}
			$result1=$models->table('image')->addAll($img);
			if($result1){
				$models->commit();
				return $result;
			}else{
				$models->rollback();
				return FALSE;
			}
		}else{
			return FALSE;	
		}
	} 


	/**
	 * 获取一个样品的信息
	 * @param $id 样品的编号
	 * @param $models 样品的型号
	 * return  false  /   $array
	 */
	public function getOneSample($id=null ,$models=null){
		
		if(empty($id)){
			$result=$this->model->relation(true)->where("s_models = '%s' ",$models)->find();
		}else{
			$result=$this->model->relation(true)->where("s_id = %d",intval($id))->find();
		}
		if($result && is_array($result)){
			return $result;
		}else{
			return FALSE;
		}
	}

	/**
	 * ajax获取样品列表
	 * @param @map  array 搜索数据
	 * $page 页码
	 */
	public function mySampleList($map,$page){
		$result=$this->model->relation(true)->where($map)->page($page.',15')->select();
		if($result && is_array($result)){
			return $result;	
		}else{
			return false;
		}
	}

	/**
	 * 获取分页的样品信息
	 * return array
	 */
	public function getSampleList($map){

		$count = $this->model->where($map)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数(15)
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $this->model->relation(true)->where($map)->order('s_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$result['page']=$show;
		$result['datalist']=$list;
		if($list && is_array($list)){
			return $result;
		}else{
			return false;
		}
	}

	/**
	 * 获取搜索样品的信息
	 * return array
	 */
	public function searchSampleList($data){

		$count = $this->model->where($data)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(15)
		$show = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $this->model->where($data)->order('s_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		$result['page']=$show;
		$result['datalist']=$list;
		if($list && is_array($list)){
			return $result;
		}else{
			return false;
		}
	}




	/**
	 * 下架/上架样品
	 * reurn boolean 
	 * success true
	 * flase   false
	 */
	public function statusSample($id,$soldout=1){
		$data['s_soldout']=$soldout;
		if(empty($id)){
			return FALSE;
		}
		$flag=$this->model->where('s_id=%d', intval($id))->data($data)->save();
		if($flag){
			return TRUE;	
		}else{
			return FALSE;
		}

	}



	/**
	 * 修改样品信息
	 * @param $data   数组
	 * return 
	 * 		success   true
	 * 		false     false
	 */
	public function updateSample($data){
		if(is_array($data) && count($data)>0){

			$sample['s_name']=$data['name'];
			$sample['s_material']=$data['material'];
			$sample['s_sizes']=$data['sizes'];
			$sample['s_price']=$data['price'];
			$sample['s_mould']=$data['mould'];
			$sample['s_soldout']= 0;

			$sample['s_neili']=$data['neili'];
			$sample['s_dadi']=$data['dadi'];
			$sample['s_chexian']=$data['chexian'];
			$sample['s_remark']=$data['remark'];



			$result=$this->model->where("s_id = %d" , intval($data['s_id']))->data($sample)->save();
			if($result){
				return TRUE;
			}else{
				return FALSE;
			}	
		}else{
			return FALSE;

		}
	}












}



