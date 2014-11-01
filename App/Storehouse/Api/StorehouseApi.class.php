<?php

/**==============库存API中心================**/




namespace Storehouse\Api;
use Storehouse\Api\Api;
use Storehouse\Model\GoodslistModel;


class StorehouseApi extends Api{


	//初始化（构造函数的一部分）
	//实例化用户模型
	function _init(){
		$this->model= new GoodslistModel();	
	}


	/**
	 * 获取仓库列表
	 * @param   $data  搜索条件
	 * return array 二维数组
	 */ 
	//获取仓库数据列表
	public function getGoodList(){
		$count=$this->model->count();
		$Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$result['page'] = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$result['datalist'] = $this->model->limit($Page->firstRow.','.$Page->listRows)->select();
		return $result;
	}


	/**
	 * 修改仓库数据信息
	 * @param   $gl_id  搜索条件
	 * return array 一维数组
	 */ 
	//修改仓库数据信息
	public function editGoodLIstUi($gl_id){
		$result=$this->model->where("gl_id='%d'",$gl_id)->find();
		return $result;
	}

	/**
	 * 修改仓库信息
	 * @param   $data 搜索条件
	 * return TRUE(success) or FALSE
	 */ 
	//修改仓库信息
	public function modifyGoodSingle($data){
		$gl_id=$data['gl_id'];
		$result=$this->model->where("gl_id=%d",intval($gl_id))->data($data)->save();
		if($result){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	/**
	 * 修改仓库信息
	 * return array 二维数组
	 */ 
	//获取货物类型
	public function getGoodType(){
		$models=D('Goodskind');
		$result=$models->select();
		return $result;
	}

	/**
	 *修改货物类型类型
	 *查询条件$gk_id
	 *return array 一维数组
	 */
	//修改货物类型类型
	public function modifyGoodType($gk_id){
		$models=D('Goodskind');
		$result=$models->where("gk_id='%d'",$gk_id)->find();
		return $result;
	}

	/**
	 *添加货物类型名称
	 *查询条件$data
	 *return 1,2,3
	 *1表示该类型已有，2表示添加成功，3表示添加失败
	 */
	//添加货物类型名称
	public function addGoodKind($data){
		$result=0;
		$this->model1=D('Goodskind');
		$find=$this->model1->where("gk_name='%s'",$data['gk_name'])->find();
		if(!empty($find)){
			$result=1;
		}else{
			$gk_id='';
			$result=$this->model1->data($data)->add();
			if($result){
				$result=2;
			}else{
				$result=3;
			}
		}
		return $result;
	}

	/***
	 *货物货物类型名称，用在添加库存上
	 *return array 二维数组
	 */
	//货物货物类型名称，用在添加库存上
	public function getGoodTypeBy(){
		$this->model=null;
		$this->model1=D("Goodskind");
		$result=$this->model1->select();
		return $result;
	}



	/***
	 *修改货物类型名称
	 *$data 查询条件
	 *return true or false
	 */
	//修改货物类型名称
	public function GoodKindSingleEdit($data){
		$this->model=null;
		$this->model1=D("Goodskind");
		$gk_id=$data['gk_id'];
		$result=$this->model1->where("gk_id=%d ",intval($gk_id))->data($data)->save();
		return $result;
	}



	//添加库存
	public function addGoodSingle($data){

		$models=M();
		$models->startTrans();
		//添加库存
		$flag1=$models->table("goodslist")->data($data)->add();


		$firm=$models->table("firm")->where("f_name = '%s'", $data['gl_supplier'])->find();


		//添加进出库记录
		$storerecord['gl_id']=$flag1;
		$storerecord['sr_number']=$data['gl_number'];
		$storerecord['sr_time']=date("Y-m-d :H:i:s");
		$storerecord['sr_totalpeice']=$data['gl_number']*$data['gl_price'];
		$storerecord['sr_settled']=1;
		$storerecord['f_id']=$firm['f_id'];
		$storerecord['sr_payedmoney']=$storerecord['sr_totalpeice'];
		$flag2=$models->table("storerecord")->data($storerecord)->add();
		if($flag2 && $flag1){
			$models->commit();
			return $flag1;
		}else{
			$models->rollback();
			return false;
		}

	}


	/**
	 * 根据样品型号获取商品信息
	 * @param $models_str 样品型号
	 * return array   一维数组
	 * 		false  0
	 */
	public function getOneGoodsByModels($models_str){
		$result=$this->model->relation(true)->where("gl_models = '%s'",$models_str)->find();
		if($result && is_array($result)){
			return $result;
		}else{
			return 0;
		}
	}

	//检测货物类型
	//return boolean
	//帐号存在true 不存在false
	//@param account
	public function checkgoodsKind($gk_name){
		if(!empty($gk_name)){
			$this->model1=D("Goodskind");
			$result=$this->model1->where("gk_name='%s'",$gk_name)->find();
			if($result&&is_array($result)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}


	//鞋底信息获取
	public function getShoseInfo($shoesmodel){
		$result="";
		$model=trim($shoesmodel);
		if(!empty($model)){
			$res=$this->model->where("gl_models='%s'",$model)->find();
			if(!empty($res)){
				$result=$res;
			}else{
				$result="";
			}
		}else{
			$result="";
		}
		return $result;
	}

	//鞋包获取
	public function getShosebaoInfo($shoesbao){
		$result="";
		$shoesmodel=trim($shoesbao);
		if(!empty($shoesmodel)){
			$res=$this->model->where("gl_models='%s'",$shoesmodel)->find();
			if(!empty($res)){
				$result=$res;
			}else{
				$result="";
			}
		}else{
			$result="";
		}
		return $result;
	}

	//中底信息获取
	public function getShoseZhongInfo($shoeszhong){
		$result="";
		$shoesmode=trim($shoeszhong);
		if(!empty($shoesmode)){
			$res=$this->model->where("gl_models='%s'",$shoesmode)->find();
			if(!empty($res)){
				$result=$res;
			}else{
				$result="";
			}
		}else{
			$result="";
		}
		return $result;
	}

	//内盒信息获取
	public function getShoseNeihe($shoesneihe){
		$result="";
		$shoesmode=trim($shoesneihe);
		if(!empty($shoesmode)){
			$res=$this->model->where("gl_models='%s'",$shoesmode)->find();
			if(!empty($res)){
				$result=$res;
			}else{
				$result="";
			}
		}else{
			$result="";
		}
		return $result;
	}

	//获取外箱信息
	public function getShoseOuter($shoes){
		$result="";
		$shoesmode=trim($shoes);
		if(!empty($shoesmode)){
			$res=$this->model->where("gl_models='%s'",$shoesmode)->find();
			if(!empty($res)){
				$result=$res;
			}else{
				$result="";
			}
		}else{
			$result="";
		}
		return $result;
	}

	//检验皮塑
	public function getShosePisu($shoespisu){
		$result=$this->model->where("gl_models='%s'",$shoespisu)->find();
		if($result&&is_array($result)){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	//检验货物型号
	public function checkModels($models){
		$model=trim($models);
		$result=$this->model->where("gl_models='%s'",$model)->find();
		if(!empty($result)){
			return TRUE;
		}else{
			return FALSE;
		}
	}


	/**
	 * 搜索仓库货物
	 * @param   $data  搜索条件
	 * return array 二维数组
	 */ 
	//获取仓库数据列表
	public function searchGoods($data){
		$count = $this->model->where($data)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$result['page'] = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$result['datalist'] = $this->model->where($data)->limit($Page->firstRow.','.$Page->listRows)->select();
		return $result;
	}


	/**
	 * 生成仓库数据文件excel
	 * @param void
	 * return string   文件名
	 */
	public function excelGoodsList(){
		$result=$this->model->select();
		if($result && is_array($result)){
			import('Vendor.PhpExcel.PHPExcel');
			$objPHPExcel= new \PHPExcel();
			$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
				->setLastModifiedBy("Maarten Balliauw")
				->setTitle("Office 2007 XLSX Test Document")
				->setSubject("Office 2007 XLSX Test Document")
				->setDescription("This document for Office 2007 XLSX.")
				->setKeywords("office 2007 ")
				->setCategory("office 2007");
			//设置表头
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', '货物名称')
				->setCellValue('B1', '货物型号')
				->setCellValue('C1', '货物商标')
				->setCellValue('D1', '货物规格')
				->setCellValue('E1', '货物材料')
				->setCellValue('F1', '货物库存量')
				->setCellValue('G1', '货物单价')
				->setCellValue('H1', '货物供应商');

			//设置内容数据
			$counter=2;
			foreach($result as $one){
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$counter, $one['gl_name'])
					->setCellValue('B'.$counter, $one['gl_models'])
					->setCellValue('C'.$counter, $one['gl_logo'])
					->setCellValue('D'.$counter, $one['gl_format'])
					->setCellValue('E'.$counter, $one['gl_material'])
					->setCellValue('F'.$counter, $one['gl_number'])
					->setCellValue('G'.$counter, $one['gl_price'])
					->setCellValue('H'.$counter, $one['gl_supplier']);

				$counter++;
			}
			$objPHPExcel->getActiveSheet()->setTitle("仓库货物清单");
			Vendor("PhpExcel.PHPExcel.IOFactory");
			$objWriter =\PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$filename=time()."orders.xls";
			$path_name=C("DOCUMENT_SAVE_PATH").$filename;
			$objWriter->save($path_name);
			return $filename;

		}else{
			return false ;
		}
	}


















}
