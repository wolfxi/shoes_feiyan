<?php
	/**************接口********/
	//样品接口api
	//所有接口都必须继承该抽象类

namespace Sample\Api;


abstract class Api{





	//模型
	protected $model;

	//构造函数
	public function __construct(){
	
		$this->_init();
	}



	//初始化操作
	abstract protected function _init();
	
	





}










?>
