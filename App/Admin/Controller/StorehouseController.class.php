<?php 

namespace Admin\Controller;
use Think\Controller;
use Storehouse\Api\StorehouseApi;
/**
* 
*/
class StorehouseController extends AdminController
{
	private $storehouseapi=null;
	public function _initialize(){
		parent::_initialize();
		$this->storehouseapi=new StorehouseApi();
		$goodskind=$this->storehouseapi->getGoodType();
		$this->assign("goodskind",$goodskind);
	}
	public function index(){

	}
	//仓库列表
	public function goodList(){
		$result=$this->storehouseapi->getGoodList();
		if(empty($result)){
			$this->error("没有相关记录");
		}else{
			$this->assign('show',$result['page']);
			$this->assign('list',$result['datalist']);
			$this->display('goodlist');
		}
	}
	//仓库信息修改
	public function editGoodListUi(){
		$gl_id=I('get.gl_id');
		if(empty($gl_id)){
			$this->error("操作错误");
		}else{
			$result=$this->storehouseapi->editGoodLIstUi($gl_id);
			if(empty($result)){
				$this->error("没有相关记录!");
			}else{
				$this->assign('result',$result);
				$this->display('editgoodlistUi');
			}
		}
	}
	//修改仓库信息
	public function modifyGood(){
		$data=I('post.');
		if(empty($data['gl_name'])||empty($data['gl_supplier'])||empty($data['gl_price'])){
			$this->error("货物名或供应商或单价不能为空!");
		}else{
			$result=$this->storehouseapi->modifyGoodSingle($data);
			if($result){
				$this->success("修改成功!");
			}else{
				$this->error("修改失败!");
			}
		}
	}
	//获取货物类型
	public function addGoodUi(){
		$result=$this->storehouseapi->getGoodType();
			$this->assign('list',$result);
			$this->display();
	}
	//添加货物类型名称
	public function addGoodKind(){
		$data=I('post.');
		if(empty($data['gk_name'])){
			$this->error("请输入货物类型名称");
		}else{
			$result=$this->storehouseapi->addGoodKind($data);
			if($result==1){
				$this->error("该类型已存在");
			}
			if($result==2){
				$this->success("添加成功");
			}
			if($result==3){
				$this->error("添加失败");
			}
		}
	}
	//修改货物类型
	public function modifyGoodKindUi($gk_id){
		$gk_id=I('get.gk_id');
		if(empty($gk_id)){
			$this->error("查询出错!");
		}else{
			$result=$this->storehouseapi->modifyGoodType($gk_id);
			$this->assign('result',$result);
			$this->display();
		}
	}



	//修改货物名称
	public function modifyGoodKind(){
		$data=I('post.');
		if(!empty($data['gk_name'])){
			$result=$this->storehouseapi->GoodKindSingleEdit($data);
			if($result){
				$this->success("修改成功!");
			}else{
				$this->error("修改失败!");
			}
		}else{
			$this->error("请填写货物类型名称");
		}
	}




	//添加一个库存时，提前获取一些信息
	public function addGoodDetailUi(){
        $casetype=$this->storehouseapi->getGoodTypeBy();
        $this->assign('casetype',$casetype);
        if(is_array($casetype)){
            $this->display('addGoodDetailUi');
        }else{
            $this->error("请先添加货物类型名称！！！");
        }
	}


	//添加库存
	public function addGoodSingle(){
		$data=I('post.');
		if(empty($data['gl_name'])||empty($data['gl_models'])||empty($data['gl_number'])||empty($data['gl_price'])){
			$this->error("请填写必填信息!");
		}else{
			$result=$this->storehouseapi->addGoodSingle($data);
			if($result){
				$this->success("添加成功!");
			}else{
				$this->error("添加失败！");
			}
		}
	}

	//检验仓库类型名称
	public function checkgoodsKind(){
		if(IS_AJAX){
			$gk_name=I('post.gkname');
			if(empty($gk_name)){
				$data['flag']=false;
				$data['message']="货物类型名称不能为空！！！";
				$this->ajaxReturn($data);
			}else{
				$result=$this->storehouseapi->checkgoodsKind($gk_name);
				if($result){
					$data['flag']=true;
					$data['message']="该类型已存在！！";
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

	//获取鞋底信息
	public function getShoseInfo(){
		if(IS_AJAX){
			$shoesmode=I('post.shoesmodel');
			if(empty($shoesmode)){
				$data['flag']=false;
				$data['message']="鞋底型号不能为空";
				$this->ajaxReturn($data);
			}else{
				$result=$this->storehouseapi->getShoseInfo($shoesmode);
				if(!empty($result)){
					$data['flag']=true;
					$data['message']=$result;
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

	//获取鞋包信息
	public function getShosebaoInfo(){
		if(IS_AJAX){
			$shoesbao=I('post.shoesbao');
			if(empty($shoesbao)){
				$data['flag']=false;
				$data['message']="鞋包型号不能为空";
				$this->ajaxReturn($data);
			}else{
				$result=$this->storehouseapi->getShosebaoInfo($shoesbao);
				if(!empty($result)){
					$data['flag']=true;
					$data['message']=$result;
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

	//z中底信息获取
	public function getShoseZhong(){
		if(IS_AJAX){
			$shoeszhong=I('post.shoeszhong');
			if(empty($shoeszhong)){
				$data['flag']=false;
				$data['message']="中底货号不能为空！";
				$this->ajaxReturn($data);
			}else{
				$result=$this->storehouseapi->getShoseZhongInfo($shoeszhong);
				if(!empty($result)){
					$data['flag']=true;
					$data['message']=$result;
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

	//内盒信息获取
	public function getShoseNeihe(){
		if(IS_AJAX){
			$shoesneihe=I('post.shoesnei');
			if(empty($shoesneihe)){
				$data['flag']=false;
				$data['message']="内盒货号不能为空！";
				$this->ajaxReturn($data);
			}else{
				$result=$this->storehouseapi->getShoseNeihe($shoesneihe);
				if(!empty($result)){
					$data['flag']=true;
					$data['message']=$result;
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

	//外箱信息获取
	public function getShoseOuter(){
		if(IS_AJAX){
			$shoesouter=I('post.shoesouter');
			if(empty($shoesouter)){
				$data['flag']=false;
				$data['message']="外箱货号不能为空";
				$this->ajaxReturn($data);
			}else{
				$result=$this->storehouseapi->getShoseOuter($shoesouter);
				if(!empty($result)){
					$data['flag']=true;
					$data['message']=$result;
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

	//小件检验
	public function getShosePisu(){
		if(IS_AJAX){
			$shoespisu=I('post.shoespisu');
			$result=$this->storehouseapi->getShosePisu($shoespisu);
			if($result){
				$data['flag']=true;
				$this->ajaxReturn($data);
			}else{
				$data['flag']=true;
				$data['message']="没有该型号皮塑";
				$this->ajaxReturn($data);
			}
		}else{
			exit();
		}
	}

	//货物型号名称检验
	public function checkModels(){
		if(IS_AJAX){
			$shoesmodel=I('post.glmodels');
			if(empty($shoesmodel)){
				$data['flag']=false;
				$data['message']="货物型号不能为空";
				$this->ajaxReturn($data);
			}else{
				$result=$this->storehouseapi->checkModels($shoesmodel);
				if($result){
					$data['flag']=true;
					$data['message']="该型号已有";
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



	/**
	 * 搜索货物
	 */
	public function searchGoods(){
		
		$search_type=I("post.search_type");
		$search_str=I("post.search_str");
		if(!empty($search_str) && !empty($search_type)){
			$map=array();
			if($search_type=="gl_models"){
				$map[$search_type]=array('LIKE',$search_str);
			}else{
				$map["gk_id"]=array('EQ',$search_type);
				$map['gl_name']=array("LIKE",$search_str);
			}
			$result=$this->storehouseapi->searchGoods($data);
			if($result){
				$this->assign("list",$result['datalist']);
				$this->assign("page",$result['page']);
				$this->assign("search","搜索".$search_type."类型下的".$search_str."结果如下：");
				$this->display();
			}else{
				$this->error("搜索的内容不存在！！！");
			}

		}else{
			$this->error("请输入要搜索的内容");
		}
	}


	/**
	 * 生成excel数据
	 */
	public function excelGoodsList(){
		if(IS_AJAX){
			$result=$this->storehouseapi->excelGoodsList();
			if($result && is_string($result)){
				$data['flag']=true;
				$data['message']=$result;
				$this->ajaxReturn($data);
			}else{
				$data['flag']=false;
				$data['message']="下载失败！！！";
				$this->ajaxReturn($data);
			}
		}else{
			exit();
		}
	}




















}
?>
