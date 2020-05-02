<?php
/*
 * @Author: your name
 * @Date: 2019-12-23 17:24:32
 * @LastEditTime : 2019-12-23 17:32:29
 * @LastEditors  : Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \LaiKeApp\app\LKT\webapp\modules\distribution\views\addsignreturnInputView.class.php
 */

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */

class addsignreturnInputView extends SmartyView
{



	public function execute()
	{

		$request = $this->getContext()->getRequest();

		$this->setAttribute("express", $request->getAttribute("express"));

		$this->setAttribute("id", $request->getAttribute("id"));

		$this->setAttribute("sNo", $request->getAttribute("sNo"));

		$this->setAttribute("otype", $request->getAttribute("otype"));

		$this->setTemplate("addsignreturn.tpl");
	}
}
