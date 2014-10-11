<?php

/**==============财务API中心================**/




namespace Finance\Api;
use Finance\Api\Api;


class FinanceApi  extends Api{


	//初始化（构造函数的一部分）
	//实例化用户模型
	function _init(){
		$this->model=D();	

	}


}



