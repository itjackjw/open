<?php
/*
 * @Author: your name
 * @Date: 2019-12-20 17:21:53
 * @LastEditTime : 2019-12-20 17:53:16
 * @LastEditors  : Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \LaiKeApp\app\LKT\webapp\modules\distribution\views\IndexreturnInputView.class.php
 */

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
class IndexreturnInputView extends SmartyView
{
	public function execute()
	{
		$request = $this->getContext()->getRequest();
		$this->setAttribute("status", $request->getAttribute("status"));
		$this->setAttribute("status1", $request->getAttribute("status1"));
		$this->setAttribute("p_name", $request->getAttribute("p_name"));
		$this->setAttribute("startdate", $request->getAttribute("startdate"));
		$this->setAttribute("enddate", $request->getAttribute("enddate"));
		$this->setAttribute("list", $request->getAttribute("list"));
		$this->setAttribute("pages_show", $request->getAttribute("pages_show"));
		$this->setAttribute("r_type", $request->getAttribute("r_type"));
		$pageto = $request->getAttribute('pageto');
		if ($pageto != '') {
			$r = rand();
			header("Content-type: application/msexcel;charset=utf-8");
			header("Content-Disposition: attachment;filename=userlist-$r.xls");
			$this->setTemplate("excel.tpl");
		} else {
			$this->setTemplate('indexreturn.tpl');
		}
	}
}
