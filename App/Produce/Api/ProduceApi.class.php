<?php

/**==============生产跟踪API中心================**/




namespace Produce\Api;
use Produce\Api\Api;
use Produce\Model\FollowproduceModel;
use Orders\Api\OrdersApi;
use Storehouse\Api\StorehouseApi;
use Produce\Model\EpibolyModel;

class ProduceApi extends Api{

	protected $ordersapi=null;

	//初始化（构造函数的一部分）
	//实例化用户模型
	function _init(){

		$this->model=new FollowproduceModel();
		$this->ordersapi=new OrdersApi();
	}


	/**
	 * 获取成产跟踪列表
	 * @param $data 筛选条件
	 * return   success   array  二维数组
	 * 			false  false
	 */
	public function getFollowProduceList($data){
		$count = $this->model->where($data)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$result['page'] = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$result['datalist'] = $this->model->relation("orders")->where($data)->limit($Page->firstRow.','.$Page->listRows)->select();
		if($result['datalist'] && is_array($result['datalist'])){
			return $result;	
		}else{
			return false;
		}
	}



	/**
	 * 获取单个生产跟踪信息
	 * @param $id  生产跟踪编号
	 * @patam $o_id 订单编号
	 * return  array  一维数组
	 */
	public function getOneFollowProduce($id ,$o_id=null){
		if(empty($id)){
			$result=$this->model->relation(true)->where("o_id = %d", intval($o_id))->find();
		}else{
			$result=$this->model->relation(true)->where("fp_id = %d", intval($id))->find();
		}
		if($result && is_array($result)){
			$result['orders']['o_attributes']=unserialize($result['orders']['o_attributes']);
			$result['orders']['o_size']=unserialize($result['orders']['o_size']);
			$result['orderstatus']=$this->ordersapi->getOrdersStatus();
			return $result;
		}else{
			return false;
		}
	}


	/**
	 * 修改成产跟踪信息
	 *@param $id  生产跟踪编号
	 *@param $data   array 需要修改的信息
	 * return  success true
	 * 			false false
	 */
	public function updateFollowProduce($data){
		$models=M();
		$models->startTrans();
		$os_name=$this->ordersapi->getOrdersStatus(null,$data['fp_status']);
		//修改订单信息
		$orders['os_id']=$data['fp_status'];
		$orders['o_number']=$data['fp_number'];
		$flag1=$models->table("orders")->where("o_id = %d", intval($data['o_id']))->data($orders)->save();

		//修改跟踪单信息
		$followproduce['fp_progress']=$data['fp_progress'];
		$followproduce['fp_status']=$os_name;
		$followproduce['fp_number']=$data['fp_number'];
		$followproduce['fp_finishnum']=$data['fp_finishnum'];
		$flag2=$models->table("followproduce")->where("fp_id = %d",$data['fp_id'])->data($followproduce)->save();

		if($flag2){
			$models->commit();
			return true;
		}else{
			$models->rollback();
			return false;
		}


		$flag2=$models->table("followproduce")->where("fp_id = %d ",intval($data['fp_id']))->data()->save();


		return $flag;
	}


	/**
	 * 生成订单所需要的材料信息
	 * @param $id  生产跟踪单号
	 * return array 一维数组
	 * 		false false
	 */
	public function getOrdersMaterial($id){

		$result=$this->ordersapi->getOneOrders($id);
		if($result && is_array($result)){

			$storehouseapi=new StorehouseApi();

			$material['order_id']=$result['o_id'];
			$material['customer']=$result['o_customer'];
			$material['shoes_models']=$result['s_models'];
			$material['number']=$result['o_number'];
			$material['mould']=$result['o_attributes']['shoes']['mould'];
			$material['sizes']=$result['o_size'];

			$material['sole']['models']=$result['o_attributes']['sole']['models'];
			$material['sole']['display_name']="鞋底";
			$material['sole']['number']=$material['number'];
			$material['sole']['stores']=$storehouseapi->getOneGoodsByModels($material['sole']['models']);

			$material['innerbox']['models']=$result['o_attributes']['innerbox']['models'];
			$material['innerbox']['display_name']="内盒";
			$material['innerbox']['number']=$material['number'];
			$material['innerbox']['stores']=$storehouseapi->getOneGoodsByModels($material['innerbox']['models']);

			$material['outerbox']['models']=$result['o_attributes']['outerbox']['models'];
			$material['outerbox']['display_name']="外箱";
			$material['outerbox']['number']=ceil($material['number']/$result['o_bunchnum']);
			$material['outerbox']['stores']=$storehouseapi->getOneGoodsByModels($material['outerbox']['models']);

			$material['insole']['models']=$result['o_attributes']['insole']['models'];
			$material['insole']['display_name']="中底";
			$material['insole']['number']=$material['number'];
			$material['insole']['stores']=$storehouseapi->getOneGoodsByModels($material['insole']['models']);

			$material['shoesbag']['models']=$result['o_attributes']['shoesbag']['models'];
			$material['shoesbag']['display_name']="鞋包";
			$material['shoesbag']['number']=$material['number'];
			$material['shoesbag']['stores']=$storehouseapi->getOneGoodsByModels($material['shoesbag']['models']);


			$material['pisu']['models']=empty($result['o_attributes']['other']['pisu']) ? "无" : $result['o_attributes']['other']['pisu'];
			$material['pisu']['display_name']="皮塑";
			$material['pisu']['number']= empty($result['o_attributes']['other']['pisu']) ? 0 : $material['number'];
			$material['pisu']['stores']=$storehouseapi->getOneGoodsByModels($material['pisu']['models']);


			$material['koujian']['models']=empty($result['o_attributes']['other']['koujian']) ? "无" : $result['o_attributes']['other']['koujian'];
			$material['koujian']['display_name']="扣件";
			$material['koujian']['number']= empty($result['o_attributes']['other']['koujian']) ? 0 : $material['number'];
			$material['koujian']['stores']=$storehouseapi->getOneGoodsByModels($material['koujian']['models']);


			$material['xieyan']['models']=empty($result['o_attributes']['other']['xieyan']) ? "无" : $result['o_attributes']['other']['xieyan'];
			$material['xieyan']['number']= empty($result['o_attributes']['other']['xieyan']) ? 0 : $material['number'];
			$material['xieyan']['display_name']="鞋眼";
			$material['xieyan']['stores']=$storehouseapi->getOneGoodsByModels($material['xieyan']['models']);


			$material['maoding']['models']=empty($result['o_attributes']['other']['maoding']) ? "无" : $result['o_attributes']['other']['maoding'];
			$material['maoding']['display_name']="帽钉";
			$material['maoding']['number']= empty($result['o_attributes']['other']['maoding']) ? 0 : $material['number'];
			$material['maoding']['stores']=$storehouseapi->getOneGoodsByModels($material['maoding']['models']);


			$material['songjintiao']['models']=empty($result['o_attributes']['other']['songjintiao']) ? "无" : $result['o_attributes']['other']['songjintiao'];
			$material['songjintiao']['display_name']="松紧条";
			$material['songjintiao']['number']= empty($result['o_attributes']['other']['songjintiao']) ? 0 : $material['number'];
			$material['songjintiao']['stores']=$storehouseapi->getOneGoodsByModels($material['songjintiao']['models']);



			$material['lalian']['models']=empty($result['o_attributes']['other']['lalian']) ? "无" : $result['o_attributes']['other']['lalian'];
			$material['lalian']['display_name']="拉链";
			$material['lalian']['number']= empty($result['o_attributes']['other']['lalian']) ? 0 : $material['number'];
			$material['lalian']['stores']=$storehouseapi->getOneGoodsByModels($material['lalian']['models']);


			$material['shuangmianjiao']['models']=empty($result['o_attributes']['other']['shuangmianjiao']) ? "无" : $result['o_attributes']['other']['shuangmianjiao'];
			$material['shuangmianjiao']['number']= empty($result['o_attributes']['other']['shuangmianjiao']) ? 0 : $material['number'];
			$material['shuangmianjiao']['display_name']="双面胶";
			$material['shuangmianjiao']['stores']=$storehouseapi->getOneGoodsByModels($material['shuangmianjiao']['models']);


			$material['xiedai']['models']=empty($result['o_attributes']['other']['xiedai']) ? "无" : $result['o_attributes']['other']['xiedai'];
			$material['xiedai']['display_name']="鞋带";
			$material['xiedai']['number']= empty($result['o_attributes']['other']['xiedai']) ? 0 : $material['number'];
			$material['xiedai']['stores']=$storehouseapi->getOneGoodsByModels($material['xiedai']['models']);


			$material['chuliji']['models']=empty($result['o_attributes']['other']['chuliji']) ? "无" : $result['o_attributes']['other']['chuliji'];
			$material['chuliji']['number']= empty($result['o_attributes']['other']['chuliji']) ? 0 : $material['number'];
			$material['chuliji']['display_name']="处理剂";
			$material['chuliji']['stores']=$storehouseapi->getOneGoodsByModels($material['chuliji']['models']);


			$material['neili']['models']=empty($result['o_attributes']['other']['neili']) ? "无" : $result['o_attributes']['other']['neili'];
			$material['neili']['number']= empty($result['o_attributes']['other']['neili']) ? 0 : $material['number'];
			$material['neili']['display_name']="内里";
			$material['neili']['stores']=$storehouseapi->getOneGoodsByModels($material['neili']['models']);


			$material['dianjiao']['models']=empty($result['o_attributes']['other']['dianjiao']) ? "无" : $result['o_attributes']['other']['dianjiao'];
			$material['dianjiao']['display_name']="垫脚";
			$material['dianjiao']['number']= empty($result['o_attributes']['other']['dianjiao']) ? 0 : $material['number'];
			$material['dianjiao']['stores']=$storehouseapi->getOneGoodsByModels($material['dianjiao']['models']);



			return $material;

		}else{
			return FALSE;	
		}
	}


	/**
	 * 生成材料文档
	 * @param $data array 一维数组
	 * return string   文档路径
	 */
	public function createBurdenDocx($result){
		import('Vendor.PhpWord.PHPWord');
		$phpword= new \PHPWord();
		$file=C("STENCIL_PATH")."burden_document.docx";
		$burdendocx=$phpword->loadTemplate($file);
		//设置相应的值
		$burdendocx->setValue("ordernumber",$result['order_id']);
		unset($result['order_id']);
		$burdendocx->setValue("customer",$result['customer']);
		unset($result['customer']);
		$burdendocx->setValue("shoesmodels",$result['shoes_models']);
		unset($result['shoes_models']);
		$burdendocx->setValue("mould",$result['mould']);
		unset($result['mould']);
		$burdendocx->setValue("number",$result['number']);
		unset($result['number']);


		//设置尺码
		for($i=19;$i<=46;$i++){
			$count=empty($result['sizes'][$i]) ? 0 : $result['sizes'][$i];
			$burdendocx->setValue("n".$i , $count);
			unset($count);
		}
		unset($result['sizes']);

		//设置相关配料表
		$row=0;
		foreach($result as $one){
			$burdendocx->setValue("material".$row."-0", $one['display_name']);
			$burdendocx->setValue("material".$row."-1", $one['models']);
			$burdendocx->setValue("material".$row."-2", $one['stores']['gl_format']);
			$burdendocx->setValue("material".$row."-3", $one['number']);
			$burdendocx->setValue("material".$row."-4", $one['stores']['gl_number']);
			$balance=(($one['stores']['gl_number']-$one['number']) >0) ? 0 : ($one['number']-$one['stores']['gl_number']);
			$burdendocx->setValue("material".$row."-5", $balance);
			$row++;
		}

		//保存文件
		$filename=time().".docx";
		$filepath=C("DOCUMENT_SAVE_PATH").$filename;
		$burdendocx->save($filepath);

		return $filename;
	}


	/**
	 * 发货操作
	 * @param $data  跟踪单号/订单号
	 * return  success true
	 * 			false  false
	 */
	public function sendGoods($data){
		$fp_result=$this->model->realtion(true)->where("fp_id =%d ",intval($data['fp_id']))->find();
		if(!$fp_result || !is_array($fp_result)){
			return false;	
		}
		$os_id=$this->ordersapi->getOrdersStatus("发货完结");


		$models=M();
		$models->startTrans();

		//修改订单信息
		$orders['os_id']=$os_id;	
		$flag1=$models->table("orders")->where("o_id = %d",intval($data['o_id']))->data($orders)->save();

		//修改跟踪单信息
		$followproduce['fp_endtime']=date("Y-m-d :H:i:s");
		$followproduce['fp_progress']="交易结束";
		$followproduce['fp_status']="发货完结";
		$followproduce['fp_finishnum']=$fp_result['fp_number'];
		$flag2=$models->table("followproduce")->where("fp_id = %d",intval($data['fp_id']))->data($followproduce)->save();

		//货物发货物品的信息
		$storehouseapi=new StorehouseApi();
		$goods=$storehouseapi->getOneGoodsByModels($fp_result['fp_models']);

		//增加发货记录
		$delivergoods['gl_id']=$goods['gl_id'];
		$delivergoods['o_id']=$data['o_id'];
		$delivergoods['dg_productname']=$goods['gl_name'];
		$delivergoods['dg_models']=$goods['gl_models'];
		$delivergoods['dg_proson']=$data['sendperson'];
		$delivergoods['dg_number']=$fp_result['fp_finishnum'];
		$delivergoods['dg_customer']=$fp_result['orders']['o_customer'];
		$delivergoods['dg_totalnum']=$fp_result['fp_number'];
		$delivergoods['dg_leavenum']=$fp_result['fp_number']-$fp_result['fp_finishnum'];
		$delivergoods['dg_price']=$fp_result['orders']['o_price'];
		$delivergoods['dg_totalprice']=$fp_result['orders']['o_price'] * $fp_result['fp_finishnum'];
		$delivergoods['dg_status']="已发货";
		$delivergoods['dg_time']=date("Y-m-d :H:i:s");
		$flag3=$models->table("delivergoods")->data($delivergoods)->add();


		//减少仓库货物记录(内盒，外箱，鞋子成品)
		$flag4=$models->table("goodslist")->where("gl_models = '%s'",$fp_result['orders']['s_models'])->setDec("gl_number",$delivergoods['dg_number']);
		$flag5=$models->table("goodslist")->where("gl_models = '%s'",$fp_result['orders']['o_attributes']['innerbox']['models'])->setDec("gl_number",$delivergoods['dg_number']);
		$flag6=$models->table("goodslist")->where("gl_models = '%s'",$fp_result['orders']['o_attributes']['outerbox']['models'])->setDec("gl_number",ceil($delivergoods['dg_number']/$fp_result['orders']['o_bunchnum']));


		//添加仓库进出库记录
		$storerecord['sr_time']=date("Y-m-d :H:i:s");
		$storerecord['sr_signer']=$data['sendperson'];
		$storerecord['sr_orderid']=$fp_result['o_id'];
		$storerecord['sr_settled']= 0;

		//鞋子出库记录
		$storerecord['gl_id']=$goods['gl_id'];
		$storerecord['sr_number']=$fp_result['fp_finishnum'];
		$flag7=$models->table("storerecord")->data($storerecord)->add();

		//内箱出库记录
		$storerecord['sr_number']=$fp_result['fp_finishnum'];
		$innerbox=$storehouseapi->getOneGoodsByModels($fp_result['orders']['o_attributes']['innerbox']['models']);
		$storerecord['gl_id']=$innerbox['gl_id'];
		$flag8=$models->table("storerecord")->data($storerecord)->add();

		//外箱出库记录
		$storerecord['sr_number']=ceil($fp_result['fp_finishnum']/$fp_result['orders']['o_bunchnum']);
		$outerbox=$storehouseapi->getOneGoodsByModels($fp_result['orders']['o_attributes']['outerbox']['models']);
		$storerecord['gl_id']=$outerbox['gl_id'];
		$flag9=$models->table("storerecord")->data($storerecord)->add();


		if($flag1 && $flag2 && $flag3 && $flag4 && $flag5 && $flag6 && $flag7 && $flag8 && $flag9){
			$models->commit();
			return TRUE;
		}else{
			$models->rooback();
			return FALSE;
		}

	}



	/**
	 * 获取外包记录列表
	 </form>
	 * @param $data  搜索条件
	 * return   success   array 二维数组
	 * 			false     false
	 */
	public function getEpibolyList($data){
		$epibolymodel=new EpibolyModel();
		$count = $epibolymodel->where($data)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$result['page'] = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$result['datalist'] = $epibolymodel->relation(true)->where($data)->order('e_id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		if($result['datalist'] && is_array($result['datalist'])){
			return $result;
		}else{
			return false;
		}
	}

	/**
	 * 获取一个外包记录
	 * @param $data   数组   搜索条件
	 * return success array  一维数组
	 * 			false false
	 */
	public function getOneEpiboly($data){
		$epibolymodel=new EpibolyModel();
		$result=$epibolymodel->relation(true)->where($data)->find();
		if($result && is_array($result)){
			return $result;
		}else{
			return false;	
		}
	}

	/**
	 * 修改外包记录
	 * @param $data   array   修改的项
	 * @param $id int   外包记录编号
	 *return   success true
	 *			false   false
	 */
	public function updateEpiboly($data,$id){
		$epibolymodel=new EpibolyModel();
		$flag=$epibolymodel->where("e_id = %d",intval($id))->data($data)->save();
		if($flag){
			return $flag;
		}else{
			return false;
		}

	}


	/**
	 * 添加外包记录
	 * @param $data   添加的数据
	 * return  int   新增的外包编号
	 */
	public function addEpiboly($data){
		$epiboly=$data;
		$epiboly['e_posttime']=date("Y-m-d :H:i:s");
		$epiboly['e_iscallback']=0;

		//查询跟踪单号
		$fp_result=$this->getOneFollowProduce(null,$epiboly['o_id']);

		$models=M();
		$models->startTrans();

		//添加外包记录
		$epiboly['fp_id']=$fp_result['fp_id'];
		$flag1=$models->table("epiboly")->data($epiboly)->add();

		//添加出库记录
		$storehouseapi=new StorehouseApi();
		$firm=$models->table("firm")->where("f_name = '%s'",$epibolu['e_contractor'])->find();
		$goods=$storehouseapi->getOneGoodsByModels($epiboly['e_models']);
		$storerecord['e_id']=$flag1;
		$storerecord['f_id']=$firm['f_id'];
		$storerecord['gl_id']=$goods['gl_id'];
		$storerecord['sr_number']=$epiboly['e_number'];
		$storerecord['sr_settled']=1;
		$storerecord['sr_orderid']=$data['o_id'];
		$storerecord['sr_signer']=$data['e_signer'];
		$storerecord['sr_time']=date("Y-m-d :H:i:s");
		$flag2=$models->table("storerecord")->data($storerecord)->add();


		//减去库存量
		$flag3=$models->table("goodslist")->where("gl_id = %d",intval($goods['gl_id']))->setDec("gl_number",$data['e_number']);

		if($flag3 && $flag2 && $flag1){
			$models->commit();
			return $flag1;
		}else{
			$models->rollback();
			return false;
		}

	}




	/**
	 * 生成跟踪单excel文件
	 * @param $status 跟踪状态
	 * return string 文件名
	 */
	public function createExcelProduce($data){
		$map['fp_status']=$data['status'];
		switch ($data['time']){
		case "month":
			$map['fp_starttime']=array("LIKE",date("Y-m")."%");
			$name=date("Y-m")."月份的订单跟踪";
			break;
		case "prevmonth":
			$map['fp_starttime']=array("LIKE",date("Y-m",strtotime("-1 month"))."%");
			$name=date("Y-m",strtotime("-1 month"))."月份的订单跟踪";
			break;
		case "prev3month":
			$map['fp_starttime']=array("EGT",date("Y-m",strtotime("-3 month")));
			$name=date("Y-m",strtotime("-3 month"))."月份至".date("Y-m-d")."的订单跟踪";
			break;
		case "halfyear":
			$map['fp_starttime']=array("EGT",date("Y-m",strtotime("-6 month")));
			$name="本半年的订单跟踪";
			break;
		case "oneyear":
			$map['fp_starttime']=array("LIKE",date("Y-")."%");
			$name="本年的订单跟踪";
			break;
		case "prevyear":
			$map['fp_starttime']=array("LIKE",date("Y-",strtotime("-1 year"))."%");
			$name="上一年的订单跟踪";
			break;
		case "all":
			$map['fp_starttime']=array();
			$name="所有的订单跟踪";
			break;
		default :
			$map['fp_starttime']=array();
			$name="所有的订单跟踪";
		}


		$fp_result=$this->model->getFollowproduceList($map);
		if($fp_result && is_array($fp_result)){
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
				->setCellValue('A1', '客户订单编号')
				->setCellValue('B1', '发货日期')
				->setCellValue('C1', '商标')
				->setCellValue('D1', '鞋样型号')
				->setCellValue('E1', '码段')
				->setCellValue('F1', '颜色')
				->setCellValue('G1', '件数')
				->setCellValue('H1', '装数')
				->setCellValue('J1', '双数')
				->setCellValue('I1', '材料')
				->setCellValue('K1', '鞋包加工户')
				->setCellValue('L1', '鞋包进库日期')
				->setCellValue('M1', '鞋包出库日期')
				->setCellValue('N1', '成型合计')
				->setCellValue('O1', '库存量')
				->setCellValue('P1', '剩余量')
				->setCellValue('Q1', '备注');

			//填充数据
			$counter=2;
			foreach($fp_result as $one){
				$sizes=unserialize($one['orders']['o_size']);
				sort($sizes);
				$min=$sizes[0];
				$max=$sizes[count($sizes)-1];
				$shoes=unserialize($one['orders']['o_attributes']);
				var_dump($shoes);exit();
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$counter, $one['o_id'])
					->setCellValue('B'.$counter, $one['fp_endtime'])
					->setCellValue('C'.$counter, $one['fp_logo'])
					->setCellValue('D'.$counter, $one['fp_models'])
					->setCellValue('E'.$counter, $min."-".$max)
					->setCellValue('F'.$counter, $shoes['shoes']['color'])
					->setCellValue('G'.$counter, ceil($one['orders']['o_number']/$one['orders']['o_bunchnum']))
					->setCellValue('H'.$counter, $one['orders']['o_bunchnum'])
					->setCellValue('I'.$counter, $shoes['shoes']['material'])
					->setCellValue('J'.$counter, $one['orders']['o_number'])
					->setCellValue('K'.$counter, $one['shoesbag']['e_contractor'])
					->setCellValue('L'.$counter, $one['shoesbag']['e_gettime'])
					->setCellValue('M'.$counter, $one['shoesbag']['e_posttime'])
					->setCellValue('N'.$counter, $one['orders']['o_number'])
					->setCellValue('O'.$counter, $one['store']['gl_number'])
					->setCellValue('P'.$counter, $one['store']['gl_number']-$one['orders']['o_number'])
					->setCellValue('Q'.$counter, $one['orders']['o_remark']);

				$counter++;
			}
			$objPHPExcel->getActiveSheet()->setTitle($name);
			Vendor("PhpExcel.PHPExcel.IOFactory");
			$objWriter =\PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$filename=time()."orders.xls";
			$path_name=C("DOCUMENT_SAVE_PATH").$filename;
			$objWriter->save($path_name);
			return $filename;
		}else{
			return false;
		}

	}






}










