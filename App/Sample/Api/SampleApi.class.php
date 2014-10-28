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



	/**
	 * 生成样品文件
	 * @param $data  筛选条件
	 * return  success $filename
	 * 			false   false
	 */
	public function createExcelSample($data){
		$sample_result=$this->model->relation(true)->where($data)->select();
		if($sample_result && is_array($sample_result)){
			import('Vendor.PhpExcel.PHPExcel');
			Vendor("PhpExcel.PHPExcel.Style");
			Vendor("PhpExcel.PHPExcel.Worksheet.Drawing");
			Vendor("PHPExcel.PHPExcel.Style.Alignment");
			Vendor("PHPExcel.PHPExcel.Style.Fill");
			$objPHPExcel= new \PHPExcel();
			$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
				->setLastModifiedBy("Maarten Balliauw")
				->setTitle("Office 2007 XLSX Test Document")
				->setSubject("Office 2007 XLSX Test Document")
				->setDescription("This document for Office 2007 XLSX.")
				->setKeywords("office 2007 ")
				->setCategory("office 2007");


			$objPHPExcel->setActiveSheetIndex(0)
				->mergeCells('A1:F1')
				->setCellValue('A1',"样品清单")
				->getRowDimension('1')->setRowHeight(30);

			$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(20);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1")->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(24);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(5);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(22);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(24);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(5);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(22);

			$counter=2;
			$array_length=count($sample_result);
			for($i=0;$i<$array_length;$i+=2){
				$one=$sample_result[$i];

				$objDrawing = new \PHPExcel_Worksheet_Drawing();
				$objDrawing->setName('Logo');
				$objDrawing->setDescription('Logo');
				$objDrawing->setPath(C("UPLOADIMG_DIR").$one['image'][0]['i_url']);
				$objDrawing->setWidth(250);
				$objDrawing->setHeight(150);
				$objDrawing->setCoordinates('A'.$counter);
				$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("B".($counter+1),$one['od_id'])
					->setCellValue("B".($counter+2),"面料")
					->setCellValue("B".($counter+3),"内里")
					->setCellValue("B".($counter+4),"大底")
					->setCellValue("B".($counter+5),"配码")
					->setCellValue("B".($counter+6),"数量")
					->setCellValue("B".($counter+7),"单价")
					->setCellValue("B".($counter+8),"备注");
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("C".($counter+1),$one['s_models'])
					->setCellValue("C".($counter+2),$one['s_material'])
					->setCellValue("C".($counter+3),$one['s_neili'])
					->setCellValue("C".($counter+4),$one['s_dadi'])
					->setCellValue("C".($counter+5),$one['s_sizes'])
					->setCellValue("C".($counter+6),"")
					->setCellValue("C".($counter+7),$one['s_price'])
					->setCellValue("C".($counter+8),$one['s_remark']);


				unset($one);
				if($array_length <= ($i+1)){
					break;
				}
				$one=$sample_result[$i+1];

				$objDrawing = new \PHPExcel_Worksheet_Drawing();
				$objDrawing->setName('Logo');
				$objDrawing->setDescription('Logo');
				$objDrawing->setPath(C("UPLOADIMG_DIR").$one['image'][0]['i_url']);
				$objDrawing->setWidth(250);
				$objDrawing->setHeight(150);
				$objDrawing->setCoordinates('D'.($counter+1));
				$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("E".($counter+1),$one['od_id'])
					->setCellValue("E".($counter+2),"面料")
					->setCellValue("E".($counter+3),"内里")
					->setCellValue("E".($counter+4),"大底")
					->setCellValue("E".($counter+5),"配码")
					->setCellValue("E".($counter+6),"数量")
					->setCellValue("E".($counter+7),"单价")
					->setCellValue("E".($counter+8),"备注");
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("F".($counter+1),$one['s_models'])
					->setCellValue("F".($counter+2),$one['s_material'])
					->setCellValue("F".($counter+3),$one['s_neili'])
					->setCellValue("F".($counter+4),$one['s_dadi'])
					->setCellValue("F".($counter+5),$one['s_sizes'])
					->setCellValue("F".($counter+6),"")
					->setCellValue("F".($counter+7),$one['s_price'])
					->setCellValue("F".($counter+8),$one['s_remark']);
				$counter=$counter+9;

			}


			Vendor("PhpExcel.PHPExcel.IOFactory");
			$objWriter =\PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$filename=time()."sample.xls";
			$path_name=C("DOCUMENT_SAVE_PATH").$filename;
			$objWriter->save($path_name);
			return $filename;

		}else{
			return false;
		}

	}








}



